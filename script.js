$(document).ready(function() {
    // Show/hide the text input field when "target_poke" button is clicked
    $('#target_poke').on('click', function() {
        $('#pokemonNameInput').toggleClass('show');
    });
});
