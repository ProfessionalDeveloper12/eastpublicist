<div id="cexit-popup" class="exit-popup mfp-hide">
    <div>
        <p>Thanks for your interest in SeedProd Pro!<br>If you have any questions or issues just <a
                href="https://www.seedprod.com/contact/" target="_blank" rel="noopener noreferrer">let us know</a>.</p>
        <p>After purchasing SeedProd Pro, you'll need to <strong>download and install the Pro version of the
                plugin</strong>, and then <strong>remove the free plugin</strong>.<br>(Don't worry, all your settings
            will be preserved.)</p>
        <p>Check out <a
                href="https://support.seedprod.com/article/49-installing-and-activating-the-coming-soon-pro-plugin?utm_source=WordPress&;utm_medium=link&utm_campaign=liteplugin"
                target="_blank" rel="noopener noreferrer">our documentation</a> for step-by-step instructions.</p>
    </div>
    <button type="button" class="exit-popup-close button-primary">OK</button>
</div>

<a href="#cexit-popup" class="exit-popup-link" style="display:none">Show inline popup</a>

<script>
jQuery('.exit-popup-link').magnificPopup({
    type: 'inline',
});

jQuery(".exit-popup-close").click(function() {
    jQuery('.exit-popup-link').magnificPopup('close');
});
</script>