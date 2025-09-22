<footer class="footer">
    <div class="footer-content">
        <div class="footer-contact">
            <h4>Contactez-nous</h4>
            <address>
                📍 123 Rue du Cinéma, 67000 Strasbourg<br>
                ☎️ <a href="tel:+33612345678">06 12 34 56 78</a><br>
                🕒 Horaires : 24h/24 tous les jours
            </address>
        </div>
        <div class="footer-links">
            <h4>Liens utiles</h4>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="films.php">Films</a></li>
                <li><a href="reservation.php">Réservations</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        &copy; 2025 Cinéphoria. Tous droits réservés.
    </div>
</footer>

<style>
.footer {
    background-color: #222;
    color: #fff;
    padding: 20px 0;
    font-family: 'Segoe UI', sans-serif;
}

.footer a {
    color: #ff4d4d;
    text-decoration: none;
}

.footer a:hover {
    text-decoration: underline;
}

.footer-content {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 15px;
}

.footer-contact, .footer-links {
    margin: 10px;
}

.footer-links ul {
    list-style: none;
    padding: 0;
}

.footer-links li {
    margin-bottom: 5px;
}

.footer-bottom {
    text-align: center;
    margin-top: 15px;
    font-size: 0.9em;
    color: #ccc;
}

@media (max-width: 768px) {
    .footer-content {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
}
</style>
