<?php

namespace App\Policies;

use App\Models\Participant;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ParticipantPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        if (! $user->isAbleTo('participant.*')) {
            foreach ($user->teams()->pluck('id') as $team_id) {
                if ($user->isAbleTo('participant.*', $team_id)) {
                    return true;
                }
            }

            return false;
        }

        return $user->isAbleTo('participant.*');
    }

//    /**
//     * Determine whether the user can view the model.
//     *
//     * @param User $user
//     * @param  \App\Models\Participant  $participant
//     * @return \Illuminate\Auth\Access\Response|bool
//     */
//    public function view(User $user, Participant $participant)
//    {
//        //
//    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        if (! $user->isAbleTo('participant.create')) {
            foreach ($user->teams()->pluck('id') as $team_id) {
                if ($user->isAbleTo('participant.create', $team_id)) {
                    return true;
                }
            }

            return false;
        }

        return $user->isAbleTo('participant.create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Participant $participant
     * @return bool
     */
    public function update(User $user, Participant $participant): bool
    {
        if (! $user->isAbleTo('participant.update')) {
            foreach ($user->teams()->pluck('id') as $participant) {
                if ($user->isAbleTo('participant.update', $participant)) {
                    return true;
                }
            }

            return false;
        }

        return $user->isAbleTo('participant.update');
    }

//    /**
//     * Determine whether the user can delete the model.
//     *
//     * @param User $user
//     * @param Participant $participant
//     * @return \Illuminate\Auth\Access\Response|bool
//     */
//    public function delete(User $user, Participant $participant)
//    {
//        //
//    }
//
//    /**
//     * Determine whether the user can restore the model.
//     *
//     * @param User $user
//     * @param Participant $participant
//     * @return \Illuminate\Auth\Access\Response|bool
//     */
//    public function restore(User $user, Participant $participant)
//    {
//        //
//    }
//
//    /**
//     * Determine whether the user can permanently delete the model.
//     *
//     * @param User $user
//     * @param Participant $participant
//     * @return \Illuminate\Auth\Access\Response|bool
//     */
//    public function forceDelete(User $user, Participant $participant)
//    {
//        //
//    }
}
