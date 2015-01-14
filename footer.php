
</div><!--/.constrain-->
<footer id="footer" class="footer">
    <div class="constrain">
        <?php $blue_custom_options = get_option('blue_theme_options'); ?>
        <? if ($blue_custom_options['footText'] != "" || $blue_custom_options['footImg'] != ""): ?>
            <p class="footer--attribution">
                <? if ($blue_custom_options['footImg'] != ""): ?>
                    <? print($blue_custom_options['footText']) ?>
                        <?php if ($blue_custom_options['footImgURL'] != ""): ?>
                            <a href="<?php echo $blue_custom_options['footImgURL']; ?>">
                        <?php endif; ?>
                    <img src="<? echo $blue_custom_options['footImg'] ?>" alt="footer image" />
                        <?php if ($blue_custom_options['footImgURL'] != ""): ?>
                            </a>
                        <?php endif; ?>
                    <? endif; ?>
            </p>
        <? endif; ?>
        <?php wp_nav_menu( array( 'container' => 'nav', 'container_class' => 'footer--menu', 'theme_location' => 'footer-links', 'fallback_cb' => 'none' ) ); ?>
    </div>
</footer><!--#footer-->
<?php wp_footer(); ?>
</body>
</html>