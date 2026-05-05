 
 <style>
    /* Grundinställningar för att footern ska stanna i botten */
body {
    margin: 0;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    font-family: 'Inter', sans-serif;
}

/* Footer-specifika stilar */
.site-footer {
    background: black;
    color: white;
    padding: 3rem 5% 1rem;
    margin-top: auto; /* Trycker ner footern om innehållet är kort */
}

.footer-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.footer-info h3, .footer-contact h3 {
    margin-top: 0;
    font-size: 1.2rem;
    color: #fff;
}

.footer-info p, .footer-contact p {
    color: #ccc;
    line-height: 1.6;
    font-size: 0.95rem;
}

.footer-bottom {
    border-top: 1px solid #333; /* Ändrade från black till #333 så den syns mot svart */
    padding-top: 1rem;
    text-align: center;
    font-size: 0.9rem;
    color: gray;
}
 </style>
 
 <footer class="site-footer">
        <div class="footer-container">
            <div class="footer-info">
                <h3>Om Oss</h3>
                <p>Vi är din destination för moderiktiga kläder. Som medlem får du alltid fri frakt och exklusiva erbjudanden</p>
            </div>
            <div class="footer-contact">
                <h3>Kontakta Oss</h3>
                <p>Email: info@ourstore.com</p>
                <p>Telefon: +46 123 456 789</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo date("Y"); ?> Our Store. All rights reserved.</p>
        </div>
    </footer>