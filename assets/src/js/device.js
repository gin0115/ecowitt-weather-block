/**
 * Device JS
 * 
 * @package PinkCrab\Ecowitt_Weather_Block
 * @license GPL-3.0-or-later
 * @since 0.1.0
 */

document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('[data-action="fetch-observation"]');
    buttons.forEach(button => {
        button.addEventListener('click', fetchObservation);
    });
});

/**
 * Fetch observation data
 */
function fetchObservation(event) {
    event.preventDefault();
    
    const button = event.target.closest('[data-action="fetch-observation"]');
    const device = button.dataset.device;
    const connection = button.dataset.connection;
    
    if (!device || !connection) {
        console.error('Missing required data: device or connection');
        return;
    }

    showLoadingState(button);

    fetch(ecowittDevice.ajaxUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            action: ecowittDevice.liveObservationAction,
            nonce: ecowittDevice.liveObservationNonce,
            device: device,
            connection: connection
        })
    })
    .then(response => response.json())
    .then(data => handleObservationSuccess(button, data))
    .catch(error => handleObservationError(button, error));
}

/**
 * Show loading state for the button
 */
function showLoadingState(button) {
    button.disabled = true;
    const svg = button.querySelector('svg');
    if (svg) svg.style.display = 'none';
    const loading = button.querySelector('.observation-loading');
    if (loading) loading.style.display = 'block';
}

/**
 * Hide loading state for the button
 */
function hideLoadingState(button) {
    button.disabled = false;
    const svg = button.querySelector('svg');
    if (svg) svg.style.display = 'block';
    const loading = button.querySelector('.observation-loading');
    if (loading) loading.style.display = 'none';
}

/**
 * Handle successful observation response
 */
function handleObservationSuccess(button, data) {
    hideLoadingState(button);
    updateObservationDisplay(button, data);
}

/**
 * Handle observation request error
 */
function handleObservationError(button, error) {
    hideLoadingState(button);
    console.error('AJAX request failed:', error);
}

/**
 * Update the observation display area
 */
function updateObservationDisplay(button, data) {
    // Find the closest card/container for this button
    const card = button.closest('.device-card, .observation-card, .card'); // adjust selectors as needed
    const displayArea = card ? card.querySelector('.observation-placeholder') : null;
    if (displayArea) {
        displayArea.innerHTML = data.view;
    }
}
