jQuery(document).ready(function($) {
    $('#zoom_connect_button').click(function(e) {
        // e.preventDefault(); // Prevent default form submission behavior

        console.log('Connecting to Zoom...');

        var clientId = $('#zoom_client_id').val();
        var clientSecret = $('#zoom_client_secret').val();

        $.ajax({
            url: doccureZoom.ajax_url,
            method: 'POST',
            data: {
                action: 'connect_to_zoom',
                client_id: clientId,
                client_secret: clientSecret,
                nonce: doccureZoom.nonce
            },
            success: function(response) {
                if (response.success) {
                    console.log('Connected to Zoom successfully!');
                    console.log('Access Token:', response.data.access_token);
                    $('#zoom_connection_status').text('Connected to Zoom successfully!');
                } else {
                    console.log('Error connecting to Zoom:', response.data.message);
                    $('#zoom_connection_status').text('Error connecting to Zoom: ' + response.data.message);
                }
            },
            error: function(xhr, status, error) {
                console.log('Error connecting to Zoom:', error);
                $('#zoom_connection_status').text('Error connecting to Zoom!');
            }
        });

        return false;

    });
});
