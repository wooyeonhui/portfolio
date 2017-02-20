(function($, window, document, undefined)
{
    "user strict";
    $(document).ready(function()
    {
        var $wilDis = "", $getChecked = "";
        $("#post-formats-select .post-format").change(function()
        {
           $getChecked = $("#post-formats-select .post-format:checked").val();
           pi_switch_header_options($getChecked);   
        });

        $getChecked = $("#post-formats-select .post-format:checked").val(); 
        pi_switch_header_options($getChecked);

        function pi_switch_header_options($getChecked)
        {
            $("#pi-post-head-options").fadeIn();
            switch ( $getChecked  )
            {
                case 'gallery':
                    $wilDis = ".zone-of-slideshow";
                    break;
                case 'quote':
                    $wilDis = ".zone-of-quote";
                    break;
                case 'video':
                    $wilDis = ".zone-of-youtube";
                    break;
                case 'audio':
                    $wilDis = ".zone-of-audio";
                    break;
                case 'image':
                    $wilDis = ".zone-of-image";
                    break;
                case 'link':
                    $wilDis = ".zone-of-link";
                  break;
                default:
                    $("#pi-post-head-options").fadeOut();
                    $wilDis = ".zone-of-standard";
                    break;
            }
            $("#pi-header-type .form-group").fadeOut();
            $("#pi-header-type "+ $wilDis).fadeIn();
        }

        $("#pi-update-map-cache").click(function()
        {
            var _nonce = $("#pi_update_map_cache_nonce_field").val();
            var $alert = $(this).prev();
            $alert.html("Updating");
            $.ajax({
                type: "POST",
                data: {action: "pi_update_map_cache", nonce: _nonce},
                url: ajaxurl,
                success: function(res)
                {
                    $alert.removeClass("hidden");
                    if ( res ="success" )
                    {
                        $alert.html("Updated");
                    }else{
                        $alert.html("Failer! Your folder don't allow write file.");
                    }
                }
            })

            return false;
        })
    })
})(jQuery, window, document);