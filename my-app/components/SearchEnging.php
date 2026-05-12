
<div class="search-wrapper" style="margin: 20px auto; max-width: 600px; text-align: center;">
    <form action="Cloths.php" method="GET" style="display: flex; gap: 10px;">
        
        <input type="text" 
               name="search" 
               placeholder="Sök på skor, märken eller kategorier..." 
               value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>"
               style="flex-grow: 1; padding: 12px; border: 2px solid #28a745; border-radius: 5px; outline: none;">

        <button type="submit" class="back-btn" style="margin: 0; cursor: pointer;">
            Sök
        </button>

        <?php if (!empty($_GET['search'])): ?>
            <a href="Cloths.php" style="padding: 12px; color: #ff0000; text-decoration: none; font-weight: bold;">
                X
            </a>
        <?php endif; ?>
        
    </form>
</div>