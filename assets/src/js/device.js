/**
 * Device page JavaScript functionality.
 *
 * @package PinkCrab\Ecowitt_Weather_Block
 * @since 0.1.0
 */

(function($) {
    'use strict';
console.log('Loaded device.js');
    /**
     * Device page functionality.
     */
    const DevicePage = {
        
        /**
         * Initialize the device page.
         */
        init: function() {
            this.bindEvents();
        },

        /**
         * Bind event handlers.
         */
        bindEvents: function() {
            // Handle "Get Observation" button clicks
            $(document).on('click', '[data-action="fetch-observation"]', this.handleGetObservation.bind(this));
        },

        /**
         * Handle "Get Observation" button click.
         *
         * @param {Event} event The click event.
         */
        handleGetObservation: function(event) {
            event.preventDefault();
            
            const $button = $(event.currentTarget);
            const deviceJson = $button.data('device-json');
            const connectionJson = $button.data('connection-json');
            
            if (!deviceJson || !connectionJson) {
                console.error('Missing required data: device or connection JSON');
                return;
            }

            // Show loading state
            this.showLoadingState($button);

            // Make AJAX request
            $.post(ecowittDevice.ajaxUrl, {
                action: ecowittDevice.liveObservationAction,
                nonce: ecowittDevice.liveObservationNonce,
                device: deviceJson,
                connection: connectionJson
            })
            .done(this.handleObservationSuccess.bind(this, $button))
            .fail(this.handleObservationError.bind(this, $button));
        },

        /**
         * Show loading state for the button.
         *
         * @param {jQuery} $button The button element.
         */
        showLoadingState: function($button) {
            $button.prop('disabled', true);
            $button.find('svg').hide();
            $button.find('.observation-loading').show();
        },

        /**
         * Hide loading state for the button.
         *
         * @param {jQuery} $button The button element.
         */
        hideLoadingState: function($button) {
            $button.prop('disabled', false);
            $button.find('svg').show();
            $button.find('.observation-loading').hide();
        },

        /**
         * Handle successful observation response.
         *
         * @param {jQuery} $button The button element.
         * @param {Object} response The AJAX response.
         */
        handleObservationSuccess: function($button, response) {
            // Hide loading state
            this.hideLoadingState($button);
            
            console.log(response);
            
            // Update the observation display area with the full response
            this.updateObservationDisplay(response);
        },

        /**
         * Handle observation request error.
         *
         * @param {jQuery} $button The button element.
         * @param {Object} xhr The XHR object.
         */
        handleObservationError: function($button, xhr) {
            // Hide loading state
            this.hideLoadingState($button);
            
            console.error('AJAX request failed:', xhr);
            this.showError('Network error occurred while fetching observation data');
        },

        /**
         * Update the observation display area.
         *
         * @param {Object} observationData The observation data.
         */
        updateObservationDisplay: function(observationData) {
            const $displayArea = $('.observation-placeholder');
            
            if ($displayArea.length === 0) {
                console.warn('No observation placeholder found');
                return;
            }

            // Display the JSON string
            const jsonString = JSON.stringify(observationData, null, 2);
            $displayArea.html(`<pre style="background: #f9f9f9; padding: 15px; border: 1px solid #ddd; border-radius: 4px; overflow-x: auto; white-space: pre-wrap; word-wrap: break-word; max-height: 600px; overflow-y: auto;">${this.escapeHtml(jsonString)}</pre>`);
        },

        /**
         * Escape HTML characters to prevent XSS.
         *
         * @param {string} text The text to escape.
         * @returns {string} The escaped text.
         */
        escapeHtml: function(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, function(m) { return map[m]; });
        },

        /**
         * Show an error message.
         *
         * @param {string} message The error message.
         */
        showError: function(message) {
            // Find or create an error display area
            let $errorArea = $('[data-error-display]');
            
            if ($errorArea.length === 0) {
                // Create error area if it doesn't exist
                $errorArea = $('<div data-error-display></div>').prependTo('body');
            }

            $errorArea.html(`<div class="notice notice-error"><p>${message}</p></div>`);
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                $errorArea.fadeOut();
            }, 5000);
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        DevicePage.init();
    });

})(jQuery);
