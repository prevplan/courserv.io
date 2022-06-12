<?php

namespace App\Console\Commands;

use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
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
        // check if courses are updated or started yesterday
        $yesterday_courses = Course::wherePublicBookable(1)
            ->where([
                ['start', '>', Carbon::yesterday()],
                ['start', '<', Carbon::now()],
            ])
            ->orWhere([
                ['updated_at', '>', Carbon::yesterday()],
                ['updated_at', '<', Carbon::now()],
            ])
            ->with('type')
            ->with('prices')
            ->get()
        ;

        if (! count($yesterday_courses)) {
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

        // and create new sitemaps...
        $sitemap_locations = Sitemap::create();
        $sitemap_courses = Sitemap::create();
        $sitemap_past_courses = Sitemap::create();

        foreach ($courses as $course) {
            // run for locations
            foreach (LaravelLocalization::getSupportedLanguagesKeys() as $languagesKey) {
                $url_location = Url::create(LaravelLocalization::getLocalizedURL($languagesKey, route('booking.overview', ['slug' => $course->type->slug, 'location' => $course->location]), [], true));
                $url_location->setLastModificationDate(Carbon::parse($course->updated_at));

                foreach (LaravelLocalization::getSupportedLanguagesKeys() as $languagesKey) {
                    $url_location->addAlternate(LaravelLocalization::getLocalizedURL($languagesKey, route('booking.overview', ['slug' => $course->type->slug, 'location' => $course->location]), [], true), $languagesKey);
                }

                $url_location->addAlternate(preg_replace('/\/'.LaravelLocalization::getDefaultLocale().'/', '', LaravelLocalization::getLocalizedURL(LaravelLocalization::getDefaultLocale(), route('booking.overview', ['slug' => $course->type->slug, 'location' => $course->location])), 1), 'x-default');

                $sitemap_locations->add($url_location);
            }

            // run for every course and price
            foreach ($course->prices as $price) {
                foreach (LaravelLocalization::getSupportedLanguagesKeys() as $languagesKey) {
                    $url_course = Url::create(LaravelLocalization::getLocalizedURL($languagesKey, route('booking', ['course' => Hashids::encode($course->id), 'price' => Hashids::encode($price->id)]), [], true));

                    $url_course->setLastModificationDate(Carbon::parse($course->updated_at));
                    $url_course->setPriority(0.5);

                    foreach (LaravelLocalization::getSupportedLanguagesKeys() as $languagesKey) {
                        $url_course->addAlternate(LaravelLocalization::getLocalizedURL($languagesKey, route('booking', ['course' => Hashids::encode($course->id), 'price' => Hashids::encode($price->id)]), [], true), $languagesKey);
                    }

                    $url_course->addAlternate(preg_replace('/\/'.LaravelLocalization::getDefaultLocale().'/', '', LaravelLocalization::getLocalizedURL(LaravelLocalization::getDefaultLocale(), route('booking', ['course' => Hashids::encode($course->id), 'price' => Hashids::encode($price->id)])), 1), 'x-default');

                    $sitemap_courses->add($url_course);
                }
            }
        }

        // days started in the last 30 days
        $past_courses = Course::wherePublicBookable(1)
            ->where([
                ['start', '>', Carbon::now()->subDays(30)],
                ['start', '<', Carbon::now()],
            ])
            ->with('type')
            ->with('prices')
            ->get()
        ;

        // create a sitemap to delete them from search-engines
        foreach ($past_courses as $course) {
            // run for every course and price
            foreach ($course->prices as $price) {
                foreach (LaravelLocalization::getSupportedLanguagesKeys() as $languagesKey) {
                    $url_past_course = Url::create(LaravelLocalization::getLocalizedURL($languagesKey, route('booking', ['course' => Hashids::encode($course->id), 'price' => Hashids::encode($price->id)]), [], true));

                    if ($course->updated_at < $course->start) {
                        $url_past_course->setLastModificationDate(Carbon::parse($course->start));
                    } else {
                        $url_past_course->setLastModificationDate(Carbon::parse($course->updated_at));
                    }
                    $url_past_course->setPriority(0.5);

                    foreach (LaravelLocalization::getSupportedLanguagesKeys() as $languagesKey) {
                        $url_past_course->addAlternate(LaravelLocalization::getLocalizedURL($languagesKey, route('booking', ['course' => Hashids::encode($course->id), 'price' => Hashids::encode($price->id)]), [], true), $languagesKey);
                    }

                    $url_past_course->addAlternate(preg_replace('/\/'.LaravelLocalization::getDefaultLocale().'/', '', LaravelLocalization::getLocalizedURL(LaravelLocalization::getDefaultLocale(), route('booking', ['course' => Hashids::encode($course->id), 'price' => Hashids::encode($price->id)])), 1), 'x-default');

                    $sitemap_past_courses->add($url_past_course);
                }
            }
        }

        $sitemap_locations->writeToFile(public_path('sitemap_locations.xml'));
        $sitemap_courses->writeToFile(public_path('sitemap_courses.xml'));
        $sitemap_past_courses->writeToFile(public_path('sitemap_past_courses.xml'));

        SitemapIndex::create()
            ->add('/sitemap_locations.xml')
            ->add('/sitemap_courses.xml')
            ->add('/sitemap_past_courses.xml')
            ->writeToFile(public_path('sitemap.xml'));

        // send a crawl ping to google
        Http::get('https://www.google.com/ping?sitemap=' . config('app.url') . '/sitemap.xml');
        Http::get('https://www.google.com/ping?sitemap=' . config('app.url') . '/sitemap_locations.xml');
        Http::get('https://www.google.com/ping?sitemap=' . config('app.url') . '/sitemap_courses.xml');
        Http::get('https://www.google.com/ping?sitemap=' . config('app.url') . '/sitemap_past_courses.xml');


        if (config('services.indexnow.key') && config('services.indexnow.url')) {
            $indexnow_urls = [];

            // generate changed links for indexnow
            foreach ($yesterday_courses as $course) {
                foreach ($course->prices as $price) {
                    foreach (LaravelLocalization::getSupportedLanguagesKeys() as $languagesKey) {
                        $indexnow_urls[] = LaravelLocalization::getLocalizedURL($languagesKey, route('booking.overview', ['slug' => $course->type->slug, 'location' => $course->location]), [], true);
                        $indexnow_urls[] = LaravelLocalization::getLocalizedURL($languagesKey, route('booking', ['course' => Hashids::encode($course->id), 'price' => Hashids::encode($price->id)]), [], true);
                    }
                }
            }

            // tell indexnow the change
            $response = Http::post(config('services.indexnow.url'), [
                'host' => preg_replace('/[a-z]+:\/\//', '', config('app.url')),
                'key' => config('services.indexnow.key'),
                'urlList' => array_values(array_unique($indexnow_urls)),
            ]);
        }
    }
}
