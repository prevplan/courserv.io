<?php

namespace App\Console\Commands;

use App\Models\Course;
use App\Models\UpdatedCourse;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Url;
use Vinkla\Hashids\Facades\Hashids;

class GenerateSitemap extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // check if courses are updated
        $updated_courses = UpdatedCourse::all();

        $updated_courses = $updated_courses->pluck('course_id')->unique();

        if (!count($updated_courses)) {
            exit();
        }

        // get all courses
        $courses = Course::wherePublicBookable(1)
            ->where('start', '>', Carbon::now())
            ->orderByDesc('updated_at')
            ->with('type')
            ->with('prices')
            ->get()
        ;

        $locales = config('localized-routes.supported-locales');

        // and create new sitemaps...
        $sitemap_locations = Sitemap::create();
        $sitemap_courses = Sitemap::create();

        foreach ($courses as $course) {
            // run for locations
            foreach ($locales as $locale) {
                $url_location = Url::create(route($locale . '.booking.overview', ['slug' => $course->type->slug, 'location' => $course->location]));
                $url_location->setLastModificationDate(Carbon::parse($course->updated_at));

                foreach ($locales as $locale) {
                    $url_location->addAlternate(route($locale . '.booking.overview', ['slug' => $course->type->slug, 'location' => $course->location]), $locale);
                }

                $url_location->addAlternate(preg_replace('/\/' . app()->getLocale() . '\//', '/', route('booking.overview', ['slug' => $course->type->slug, 'location' => $course->location])), 'x-default');

                $sitemap_locations->add($url_location);
            }

            // run for every course and price
            foreach ($course->prices as $price) foreach ($locales as $locale) {
                $url_course = Url::create(route($locale . '.booking', ['course' => Hashids::encode($course->id), 'price' => Hashids::encode($price->id)]));

                $url_course->setLastModificationDate(Carbon::parse($course->updated_at));
                $url_course->setPriority(0.5);

                foreach ($locales as $locale) {
                    $url_course->addAlternate(route($locale . '.booking', ['course' => Hashids::encode($course->id), 'price' => Hashids::encode($price->id)]), $locale);
                }

                $url_course->addAlternate(preg_replace('/\/' . app()->getLocale() . '\//', '/', route('booking', ['course' => Hashids::encode($course->id), 'price' => Hashids::encode($price->id)])), 'x-default');

                $sitemap_courses->add($url_course);
            }
        }

        $sitemap_locations->writeToFile(public_path('sitemap_locations.xml'));
        $sitemap_courses->writeToFile(public_path('sitemap_courses.xml'));

        SitemapIndex::create()
            ->add('/sitemap_locations.xml')
            ->add('/sitemap_courses.xml')
            ->writeToFile(public_path('sitemap.xml'));

        // send a crawl ping to google
        Http::get('https://www.google.com/ping?sitemap=' . config('app.url') . '/sitemap.xml');
        Http::get('https://www.google.com/ping?sitemap=' . config('app.url') . '/sitemap_locations.xml');
        Http::get('https://www.google.com/ping?sitemap=' . config('app.url') . '/sitemap_courses.xml');


        if (config('services.indexnow.key')) {
            // only get changed courses
            $courses = Course::whereIn('id', $updated_courses)
                ->where('public_bookable', 1)
                ->with('prices')
                ->get();

            $indexnow_urls = [];

            // generate changed links for indexnow.org
            foreach ($courses as $course) foreach ($course->prices as $price) foreach ($locales as $locale) {
                $indexnow_urls[] = route($locale . '.booking.overview', ['slug' => $course->type->slug, 'location' => $course->location]);
                $indexnow_urls[] = route($locale . '.booking', ['course' => Hashids::encode($course->id), 'price' => Hashids::encode($price->id)]);
            }

            // tell indexnow.org the change
            $response = Http::post('https://api.indexnow.org/indexnow', [
                'host' => preg_replace('/[a-z]+:\/\//', '', config('app.url')),
                'key' => config('services.indexnow.key'),
                'urlList' => array_values(array_unique($indexnow_urls)),
            ]);
        }

        UpdatedCourse::whereIn('course_id', $updated_courses)
            ->delete();
    }
}
