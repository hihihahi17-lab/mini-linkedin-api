<?php

namespace App\Listeners;

use App\Events\CandidatureDeposee;

class LogCandidatureDeposee
{
    public function handle(CandidatureDeposee $event): void
    {
        $candidature = $event->candidature->load('profil.user', 'offre');

        $nomCandidat = $candidature->profil->user->name;
        $titreOffre  = $candidature->offre->titre;
        $date        = now()->toDateTimeString();

        $message = "[{$date}] NOUVELLE CANDIDATURE — "
                 . "Candidat: {$nomCandidat} — "
                 . "Offre: {$titreOffre}\n";

        file_put_contents(
            storage_path('logs/candidatures.log'),
            $message,
            FILE_APPEND
        );
    }
}