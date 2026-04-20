<?php

namespace App\Listeners;

use App\Events\StatutCandidatureMis;

class LogStatutCandidatureMis
{
    public function handle(StatutCandidatureMis $event): void
    {
        $nouveauStatut = $event->candidature->statut;
        $ancienStatut  = $event->ancienStatut;
        $date          = now()->toDateTimeString();

        $message = "[{$date}] STATUT MODIFIÉ — "
                 . "Ancien: {$ancienStatut} — "
                 . "Nouveau: {$nouveauStatut}\n";

        file_put_contents(
            storage_path('logs/candidatures.log'),
            $message,
            FILE_APPEND
        );
    }
}