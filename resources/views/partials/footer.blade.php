<div id="chat-widget" class="position-fixed bottom-0 end-0 mb-4 me-4">
    <button id="chat-toggle" class="btn btn-primary rounded-pill d-flex align-items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
        </svg>
        {{ app()->getLocale() == 'fr' ? 'Contactez-nous' : 'Contact us' }}
    </button>

    <div id="chat-window" class="card shadow d-none" style="width: 300px;">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                {{-- <img src="{{ asset('path/to/logo.png') }}" alt="Art d'Histoire" class="rounded-circle me-2"
                    width="30" height="30"> --}}
                <span>Art d'Histoire</span>
            </div>
            <button id="close-chat" class="btn-close btn-close-white"></button>
        </div>
        <div class="card-body">
            <div class="text-muted small mb-3">{{ now()->format('g:i A') }}</div>
            <div id="chat-messages">
                <p>{{ app()->getLocale() == 'fr' ? 'Bonjour, veuillez laisser vos coordonnées pour pouvoir nous envoyer un message ici.' : 'Hello, please leave your contact information to send us a message here.' }}
                </p>
            </div>
            <form id="contact-form" class="mt-3">
                @csrf
                <div class="mb-3">
                    <input type="text" class="form-control bottom-border" id="name" name="name"
                        placeholder="{{ app()->getLocale() == 'fr' ? 'Nom' : 'Name' }}" required>
                </div>
                <div class="mb-3">
                    <input type="email" class="form-control bottom-border" id="email" name="email"
                        placeholder="Email" required>
                </div>
                <div class="mb-3">
                    <textarea class="form-control bottom-border" id="message" name="message" rows="3"
                        placeholder="{{ app()->getLocale() == 'fr' ? 'Message' : 'Message' }}" required></textarea>
                </div>
                <button type="submit" class="btn btn-dark w-100">OK</button>
            </form>
        </div>
    </div>
</div>

<style>
    .bottom-border {
        border: none;
        border-bottom: 1px solid black;
        border-radius: 0;
        padding-left: 0;
        padding-right: 0;
    }

    .bottom-border:focus {
        box-shadow: none;
        border-color: black;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatToggle = document.getElementById('chat-toggle');
        const chatWindow = document.getElementById('chat-window');
        const closeChat = document.getElementById('close-chat');
        const contactForm = document.getElementById('contact-form');
        const csrfToken = document.querySelector('meta[name="csrf-token"]');

        function toggleChat() {
            chatWindow.classList.toggle('d-none');
            chatToggle.classList.toggle('d-none');
        }

        chatToggle.addEventListener('click', toggleChat);

        closeChat.addEventListener('click', toggleChat);

        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(contactForm);

            if (csrfToken) {
                fetch('/contact', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        const chatMessages = document.getElementById('chat-messages');
                        if (data.success) {
                            chatMessages.innerHTML +=
                                '<p class="text-success">{{ app()->getLocale() == 'fr' ? 'Votre message a été envoyé avec succès!' : 'Your message has been sent successfully!' }}</p>';
                            contactForm.reset();
                        } else {
                            chatMessages.innerHTML +=
                                '<p class="text-danger">{{ app()->getLocale() == 'fr' ? 'Une erreur s\'est produite. Veuillez réessayer.' : 'An error occurred. Please try again.' }}</p>';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        const chatMessages = document.getElementById('chat-messages');
                        chatMessages.innerHTML +=
                            '<p class="text-danger">{{ app()->getLocale() == 'fr' ? 'Une erreur s\'est produite. Veuillez réessayer.' : 'An error occurred. Please try again.' }}</p>';
                    });
            } else {
                console.error('CSRF token not found');
                const chatMessages = document.getElementById('chat-messages');
                chatMessages.innerHTML +=
                    '<p class="text-danger">{{ app()->getLocale() == 'fr' ? 'Une erreur s\'est produite. Veuillez réessayer.' : 'An error occurred. Please try again.' }}</p>';
            }
        });
    });
</script>

<footer class="d-flex flex-column align-items-center justify-content-center my-5">
    <div>
        <div class="d-md-flex gap-5 text-center">
            <p>{{ app()->getLocale() == 'fr' ? '"L\'art est avec nous pour que nous ne périssions pas de la vérité." Friedrich Nietzsche' : '"Art is with us in order that we may not perish through truth." Friedrich Nietzsche' }}
            </p>
            <p class="d-none d-md-block">|</p>
            <p>adh@artdhistoire.com</p>
        </div>
        {{-- <div>
            <p style="text-align: center;">&copy; {{ date('Y') }} Mi Sitio Web. Todos los derechos reservados.</p>
        </div> --}}
    </div>
    <div>
        <a href="">
            <img src="{{ asset('img/fb-logo.webp') }}" alt="">
        </a>
        <a href="">
            <img src="{{ asset('img/twitter-logo.webp') }}" alt="">
        </a>
    </div>
</footer>
