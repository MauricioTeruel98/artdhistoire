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
                <h1 class="baskeville-italic">{{ app()->getLocale() == 'fr' ? 'Art d\'Histoire' : 'Art d\'Histoire' }}</h1>
                <h2 class="baskeville-italic green-font">{{ app()->getLocale() == 'fr' ? 'La première chaîne gratuite consacrée aux arts du XIXe' : 'The first free channel dedicated to the arts of the 19th century' }}</h2>
                <p class="subtitle baskeville-italic green-font">{{ app()->getLocale() == 'fr' ? 'L\'excellence démocratisée' : 'Democratized excellence' }}</p>

                <p>{{ app()->getLocale() == 'fr' ? 'Art d\'Histoire est une société fondée en 2017 dont le but est de diffuser des conférences d\'histoire de l\'art destinées à partager un contenu universitaire auprès d\'un large public.' : 'Art d\'Histoire is a company founded in 2017 whose purpose is to broadcast history of art lectures intended to share university content with a wide audience.' }}</p>

                <p>{{ app()->getLocale() == 'fr' ? 'Cette mission de vulgarisation des savoirs a été conçue en français et anglais et a positionné depuis 2018 près d\'un million d\'auditeurs.' : 'This mission of popularization of knowledge has been designed in French and English and has positioned since 2018 near one million listeners.' }}</p>

                <h3 class="mt-5 mb-3">{{ app()->getLocale() == 'fr' ? 'Nous contacter' : 'Contact us' }}</h3>
                <form>
                    <input type="text" class="form-control" placeholder="{{ app()->getLocale() == 'fr' ? 'Nom *' : 'Name *' }}" required>
                    <input type="email" class="form-control" placeholder="Email *" required>
                    <textarea class="form-control" rows="3" placeholder="{{ app()->getLocale() == 'fr' ? 'Message' : 'Message' }}"></textarea>
                    <button type="submit" class="btn btn-custom mt-2">{{ app()->getLocale() == 'fr' ? 'Envoyer' : 'Send' }}</button>
                </form>
            </div>

            <div class="col-md-6">
                <h1 class="baskeville-italic">{{ app()->getLocale() == 'fr' ? 'LISA' : 'LISA' }}</h1>
                <h2 class="baskeville-italic green-font">{{ app()->getLocale() == 'fr' ? 'Un portail combinant vidéos et encyclopédie' : 'A portal combining videos and encyclopedia' }}</h2>
                <p class="subtitle baskeville-italic green-font">{{ app()->getLocale() == 'fr' ? 'La pérennité et l\'irréfutabilité en deux clics' : 'Permanence and irrefutability in two clicks' }}</p>

                <p>{{ app()->getLocale() == 'fr' ? 'LISA pour Learning Interactive Smart Art est notre nouveau portail d\'histoire dédié aux arts du XIXe.' : 'LISA for Learning Interactive Smart Art is our new history portal dedicated to the arts of the 19th century.' }}</p>

                <p>{{ app()->getLocale() == 'fr' ? 'Les vidéos d\'Art d\'Histoire sont désormais interactives : capables dans la foulée des carrefours de popup cliquables donnant accès aux fiches de l\'encyclopédie LISA. Chacune de ces fiches est intégralement citée par des sources primaires fiables, elles-mêmes sont accessibles en quelques clics.' : 'The videos of Art d\'Histoire are now interactive: capable in a flash of popup intersections giving access to the LISA encyclopedia cards. Each of these cards is fully cited by reliable primary sources, which are themselves accessible in just a few clicks.' }}</p>

                <p>{{ app()->getLocale() == 'fr' ? 'Cette interactivité assure la validité du contenu, tout en maintenant le lecteur actif et attentif.' : 'This interactivity ensures the validity of the content, while keeping the reader active and attentive.' }}</p>

                <p>{{ app()->getLocale() == 'fr' ? 'LISA entre en phase de test.' : 'LISA is entering the testing phase.' }}</p>

                <p class="mt-4">
                    <span class="highlight">{{ app()->getLocale() == 'fr' ? 'Il nous aura fallu des années,' : 'It took us years,' }}</span><br>
                    <span class="highlight">{{ app()->getLocale() == 'fr' ? 'il ne vous faudra que quelques clics.' : 'it will only take you a few clicks.' }}</span>
                </p>

                <h3 class="mt-4">
                    {{ app()->getLocale() == 'fr' ? 'Un constat' : 'A fact' }}
                </h3>
                <p>{{ app()->getLocale() == 'fr' ? 'À l\'heure où l\'on numériste des milliards de lignes de textes, où des millions de documents sont commentés des milliers d\'oeuvres, la capacité d\'information est submergée : trop lexique, trop de textes, trop de fautes.' : 'At the time when we digitize billions of lines of texts, where millions of documents are commented by thousands of works, the information capacity is overwhelmed: too many words, too many texts, too many mistakes.' }}</p>

                <h3>
                    {{ app()->getLocale() == 'fr' ? 'Notre solution' : 'Our solution' }}
                </h3>
                <p>{{ app()->getLocale() == 'fr' ? 'Compiler des sources irréfutables, les sources primaires, et les rendre compatibles en les intégrant à nos vidéos.' : 'Compile reliable sources, primary sources, and make them compatible by integrating them into our videos.' }}</p>

                <h3>
                    {{ app()->getLocale() == 'fr' ? 'Une mission' : 'A mission' }}
                </h3>
                <p>{{ app()->getLocale() == 'fr' ? 'Accélérer la recherche des professionnels, optimiser l\'apprentissage des étudiants, passionner les amateurs, démocratiser le savoir en publiant sur le web, dépasser les barrières de la langue en éditant en français et anglais.' : 'Accelerate the research of professionals, optimize the learning of students, arouse enthusiasts, popularize knowledge by publishing on the web, overcome the language barriers by editing in French and English.' }}</p>

                <h3>
                    {{ app()->getLocale() == 'fr' ? 'Une technologie' : 'A technology' }}
                </h3>
                <p>{{ app()->getLocale() == 'fr' ? 'LISA combine les vidéos d\'Art d\'Histoire avec une encyclopédie grâce à une technologie de vidéo interactive en association avec l\'Inria. Ce sont en moyenne 150 fiches qui viennent ainsi étayer le discours présenté en conférence, soit environ 1500 liens intelligents vers des sources primaires numérisées, le plus souvent par Gallica.' : 'LISA combines the videos of Art d\'Histoire with an encyclopedia thanks to an interactive video technology in association with Inria. On average, 150 cards thus support the presentation of the speech given in the lecture, that is about 1500 intelligent links to digitized primary sources, most often by Gallica.' }}</p>
            </div>
        </div>
    </div>
@endsection
