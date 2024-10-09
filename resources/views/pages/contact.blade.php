@extends('layouts.applayout')

@section('title', "Art d'Histoire | About ")

@section('header')

    <style>
        h1 {
            color: #2d2654;
            font-size: 24px;
            margin-bottom: 0;
        }

        h2 {
            color: #4a4a4a;
            font-size: 18px;
            font-weight: normal;
            margin-top: 5px;
        }

        .subtitle {
            color: #6a6a6a;
            font-style: italic;
        }

        .btn-custom {
            background-color: #2d2654;
            color: white;
            border: none;
            padding: 10px 20px;
        }

        .btn-custom:hover {
            background-color: #3a3169;
            color: white;
        }

        .form-control {
            margin-bottom: 10px;
        }

        .highlight {
            color: #2d2654;
            font-weight: bold;
        }

        h1{
            font-weight: bold;
        }

        .green-font{
            color: #204007 !important;
            font-weight: bold;
        }
    </style>

@endsection

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <h1 class="baskeville-italic">Art d'Histoire,</h1>
                <h2 class="baskeville-italic green-font">La première chaîne gratuite consacrée aux arts du XIXe</h2>
                <p class="subtitle baskeville-italic green-font">L'excellence démocratisée</p>

                <p>Art d'Histoire est une société fondée en 2017 dont le but est de diffuser des conférences d'histoire de
                    l'art destinées à partager un contenu universitaire auprès d'un large public.</p>

                <p>Cette mission de vulgarisation des savoirs a été conçue en français et anglais et a positionné depuis
                    2018 près d'un million d'auditeurs.</p>

                <h3 class="mt-5 mb-3">Nous contacter</h3>
                <form>
                    <input type="text" class="form-control" placeholder="Nom *" required>
                    <input type="email" class="form-control" placeholder="Email *" required>
                    <textarea class="form-control" rows="3" placeholder="Message"></textarea>
                    <button type="submit" class="btn btn-custom mt-2">Envoyer</button>
                </form>
            </div>

            <div class="col-md-6">
                <h1 class="baskeville-italic">LISA,</h1>
                <h2 class="baskeville-italic green-font">Un portail combinant vidéos et encyclopédie</h2>
                <p class="subtitle baskeville-italic green-font">La pérennité et l'irréfutabilité en deux clics</p>

                <p>LISA pour Learning Interactive Smart Art est notre nouveau portail d'histoire dédié aux arts du XIXe.</p>

                <p>Les vidéos d'Art d'Histoire sont désormais interactives : capables dans la foulée des carrefours de popup
                    cliquables donnant accès aux fiches de l'encyclopédie LISA. Chacune de ces fiches est intégralement
                    citée par des sources primaires fiables, elles-mêmes sont accessibles en quelques clics.</p>

                <p>Cette interactivité assure la validité du contenu, tout en maintenant le lecteur actif et attentif.</p>

                <p>LISA entre en phase de test.</p>

                <p class="mt-4">
                    <span class="highlight">Il nous aura fallu des années,</span><br>
                    <span class="highlight">il ne vous faudra que quelques clics.</span>
                </p>

                <h3 class="mt-4">Un constat</h3>
                <p>À l'heure où l'on numériste des milliards de lignes de textes, où des millions de documents sont
                    commentés des milliers d'oeuvres, la capacité d'information est submergée : trop lexique, trop de
                    textes, trop de fautes.</p>

                <h3>Notre solution</h3>
                <p>Compiler des sources irréfutables, les sources primaires, et les rendre compatibles en les intégrant à
                    nos vidéos.</p>

                <h3>Une mission</h3>
                <p>Accélérer la recherche des professionnels, optimiser l'apprentissage des étudiants, passionner les
                    amateurs, démocratiser le savoir en publiant sur le web, dépasser les barrières de la langue en éditant
                    en français et anglais.</p>

                <h3>Une technologie</h3>
                <p>LISA combine les vidéos d'Art d'Histoire avec une encyclopédie grâce à une technologie de vidéo
                    interactive en association avec l'Inria. Ce sont en moyenne 150 fiches qui viennent ainsi étayer le
                    discours présenté en conférence, soit environ 1500 liens intelligents vers des sources primaires
                    numérisées, le plus souvent par Gallica.</p>
            </div>
        </div>
    </div>
@endsection
