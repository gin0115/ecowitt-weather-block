/**
 * Settings JS
 * 
 * @package PinkCrab\Ecowitt_Weather_Block
 * @license GPL-3.0-or-later
 * @since 0.1.0
 */

console.log('settings.js');

/**
 * Main Settings Manager
 * Handles all settings page interactions including dynamic connection panels
 */
class SettingsManager {
    constructor() {
        this.connections = new Map();
        this.observer = null;
        this.init();
    }

    /**
     * Initialize the settings manager
     */
    init() {
        this.setupMutationObserver();
        this.initializeExistingConnections();
        this.initializeNewConnectionForm();
    }

    /**
     * Setup mutation observer to watch for dynamically added connections
     */
    setupMutationObserver() {
        this.observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                mutation.addedNodes.forEach((node) => {
                    if (node.nodeType === Node.ELEMENT_NODE) {
                        // Check if the added node is a connection card
                        if (node.matches && node.matches('[id^="connection-"]')) {
                            this.initializeConnection(node);
                        }
                        // Check for connection cards within the added node
                        const connectionCards = node.querySelectorAll && node.querySelectorAll('[id^="connection-"]');
                        if (connectionCards) {
                            connectionCards.forEach(card => this.initializeConnection(card));
                        }
                    }
                });
            });
        });

        // Start observing
        this.observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }

    /**
     * Initialize existing connections on page load
     */
    initializeExistingConnections() {
        const existingConnections = document.querySelectorAll('[id^="connection-"]');
        existingConnections.forEach(card => this.initializeConnection(card));
    }

    /**
     * Initialize new connection form functionality
     */
    initializeNewConnectionForm() {
        const showFormBtn = document.querySelector('[data-action="show-new-connection-form"]');
        const newConnectionSection = document.getElementById('new-connection');
        const newConnectionForm = document.getElementById('new-connection-form');
        
        if (showFormBtn && newConnectionSection && newConnectionForm) {
            showFormBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.showNewConnectionForm(newConnectionSection, newConnectionForm);
            });
        }

        // Also look for cancel button in new connection form
        const cancelBtn = newConnectionForm && newConnectionForm.querySelector('[data-action="cancel-edit"]');
        if (cancelBtn && newConnectionSection && newConnectionForm) {
            cancelBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.hideNewConnectionForm(newConnectionSection, newConnectionForm);
            });
        }
    }

    /**
     * Show the new connection form
     * @param {HTMLElement} newConnectionSection 
     * @param {HTMLElement} newConnectionForm 
     */
    showNewConnectionForm(newConnectionSection, newConnectionForm) {
        newConnectionSection.style.display = 'none';
        newConnectionForm.style.display = 'block';
        newConnectionForm.classList.add('is-active');
        
        // Force the connection edit section to show using CSS
        const style = document.createElement('style');
        style.id = 'new-connection-form-style';
        style.textContent = '#new-connection-form .connection__edit { display: block !important; }';
        document.head.appendChild(style);
        
        // Trigger custom event
        document.dispatchEvent(new CustomEvent('connection:new-form-shown', {
            detail: { newConnectionForm }
        }));
    }

    /**
     * Hide the new connection form
     * @param {HTMLElement} newConnectionSection 
     * @param {HTMLElement} newConnectionForm 
     */
    hideNewConnectionForm(newConnectionSection, newConnectionForm) {
        newConnectionForm.style.display = 'none';
        newConnectionForm.classList.remove('is-active');
        newConnectionSection.style.display = 'block';
        
        // Remove the CSS override style
        const style = document.getElementById('new-connection-form-style');
        if (style) {
            style.remove();
        }
        
        // Clear form data if needed
        const form = newConnectionForm.querySelector('form');
        if (form) {
            form.reset();
        }
        
        // Trigger custom event
        document.dispatchEvent(new CustomEvent('connection:new-form-hidden', {
            detail: { newConnectionSection }
        }));
    }

    /**
     * Initialize a single connection card
     * @param {HTMLElement} connectionCard 
     */
    initializeConnection(connectionCard) {
        if (!connectionCard || !connectionCard.id) return;

        const connectionId = this.extractConnectionId(connectionCard.id);
        
        // Skip if already initialized
        if (this.connections.has(connectionId)) return;

        const connectionHandler = new ConnectionHandler(connectionCard, connectionId);
        this.connections.set(connectionId, connectionHandler);
    }

    /**
     * Extract connection ID from element ID
     * @param {string} elementId 
     * @returns {string}
     */
    extractConnectionId(elementId) {
        return elementId.replace('connection-', '');
    }

    /**
     * Remove a connection from the manager
     * @param {string} connectionId 
     */
    removeConnection(connectionId) {
        const handler = this.connections.get(connectionId);
        if (handler) {
            handler.destroy();
            this.connections.delete(connectionId);
        }
    }

    /**
     * Handle saving a new connection
     * @param {string} connectionId 
     * @param {Object} formData 
     * @param {HTMLElement} connectionCard 
     */
    handleNewConnectionSave(connectionId, formData, connectionCard) {
        // Use HTML5 form validation instead of manual validation
        const form = connectionCard.querySelector('form');
        if (form && !form.checkValidity()) {
            // If form is invalid, show validation messages and stop
            form.reportValidity();
            return;
        }

        // Generate a new connection ID (replace the temporary one)
        const newConnectionId = 'connection_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
        
        // Get the connection template
        const template = window.ecowittSettings?.connectionTemplate;
        if (!template) {
            console.error('Connection template not available');
            return;
        }

        // Replace template variables with actual values
        let connectionHtml = template
            .replace(/\{key\}/g, newConnectionId)
            .replace(/\{name\}/g, formData[`connection_name[${connectionId}]`] || '')
            .replace(/\{description\}/g, formData[`connection_description[${connectionId}]`] || '')
            .replace(/\{application_key\}/g, formData[`connection_application_key[${connectionId}]`] || '')
            .replace(/\{api_key\}/g, formData[`connection_api_key[${connectionId}]`] || '')
            .replace(/\{mac_address\}/g, formData[`connection_mac_address[${connectionId}]`] || '');

        // Find or create the connections list
        let connectionsList = document.querySelector('.connections-list');
        if (!connectionsList) {
            // If no connections exist, we need to replace the empty state
            const connectionsContainer = document.querySelector('.connections-container');
            const emptyState = connectionsContainer.querySelector('.connections-empty');
            
            if (emptyState) {
                emptyState.remove();
            }
            
            // Create the connections list
            connectionsList = document.createElement('div');
            connectionsList.className = 'connections-list';
            connectionsContainer.insertBefore(connectionsList, connectionsContainer.querySelector('.new-connection'));
        }

        // Add the new connection to the list
        connectionsList.insertAdjacentHTML('beforeend', connectionHtml);

        // Hide the new connection form
        const newConnectionSection = document.getElementById('new-connection');
        const newConnectionForm = document.getElementById('new-connection-form');
        this.hideNewConnectionForm(newConnectionSection, newConnectionForm);

        // Initialize the new connection (it will be picked up by the mutation observer automatically)
        // But we can also manually trigger it for immediate functionality
        const newConnectionCard = document.getElementById('connection-' + newConnectionId);
        if (newConnectionCard) {
            this.initializeConnection(newConnectionCard);
        }

        // Trigger success event
        document.dispatchEvent(new CustomEvent('connection:new-connection-added', {
            detail: { 
                connectionId: newConnectionId,
                formData: formData,
                connectionCard: newConnectionCard
            }
        }));

        console.log('New connection added:', newConnectionId);
    }

    /**
     * Cleanup when needed
     */
    destroy() {
        if (this.observer) {
            this.observer.disconnect();
        }
        this.connections.forEach(handler => handler.destroy());
        this.connections.clear();
    }
}

/**
 * Connection Handler
 * Manages individual connection panel interactions
 */
class ConnectionHandler {
    constructor(connectionCard, connectionId) {
        this.connectionCard = connectionCard;
        this.connectionId = connectionId;
        this.elements = {};
        this.eventListeners = [];
        
        this.init();
    }

    /**
     * Initialize the connection handler
     */
    init() {
        this.cacheElements();
        this.bindEvents();
    }

    /**
     * Cache DOM elements for this connection
     */
    cacheElements() {
        this.elements = {
            viewState: this.connectionCard.querySelector('.connection__view'),
            editState: this.connectionCard.querySelector('.connection__edit'),
            editBtn: this.connectionCard.querySelector('[data-action="edit-connection"]'),
            cancelBtn: this.connectionCard.querySelector('[data-action="cancel-edit"]'),
            deleteBtn: this.connectionCard.querySelector('[data-action="delete-connection"]'),
            saveBtn: this.connectionCard.querySelector('[data-action="save-connection"]')
        };

        // Ensure save button is not a submit type to prevent form submission
        const { saveBtn } = this.elements;
        if (saveBtn && saveBtn.type === 'submit') {
            saveBtn.type = 'button';
            console.log('Changed save button type from submit to button');
        }
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        this.bindEditConnection();
        this.bindCancelEdit();
        this.bindDeleteConnection();
        this.bindSaveConnection();
    }

    /**
     * Bind edit connection functionality
     */
    bindEditConnection() {
        const { editBtn, editState, viewState } = this.elements;
        
        if (editBtn && editState && viewState) {
            const handler = (e) => {
                e.preventDefault();
                this.enterEditMode();
            };
            
            editBtn.addEventListener('click', handler);
            this.eventListeners.push({ element: editBtn, event: 'click', handler });
        }
    }

    /**
     * Bind cancel edit functionality
     */
    bindCancelEdit() {
        const { cancelBtn, editState, viewState } = this.elements;
        
        if (cancelBtn && editState && viewState) {
            const handler = (e) => {
                e.preventDefault();
                this.exitEditMode();
            };
            
            cancelBtn.addEventListener('click', handler);
            this.eventListeners.push({ element: cancelBtn, event: 'click', handler });
        }
    }

    /**
     * Bind delete connection functionality
     */
    bindDeleteConnection() {
        const { deleteBtn } = this.elements;
        
        if (deleteBtn) {
            const handler = (e) => {
                e.preventDefault();
                this.handleDelete();
            };
            
            deleteBtn.addEventListener('click', handler);
            this.eventListeners.push({ element: deleteBtn, event: 'click', handler });
        }
    }

    /**
     * Bind save connection functionality
     */
    bindSaveConnection() {
        const { saveBtn } = this.elements;
        
        if (saveBtn) {
            const handler = (e) => {
                console.log('Save button clicked, preventing default');
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                this.handleSave();
                return false;
            };
            
            saveBtn.addEventListener('click', handler);
            this.eventListeners.push({ element: saveBtn, event: 'click', handler });
        }

        // Also bind to form submission to catch any other submit triggers
        const form = this.connectionCard.querySelector('form');
        if (form) {
            const formHandler = (e) => {
                console.log('Form submit triggered, preventing default');
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                this.handleSave();
                return false;
            };
            
            form.addEventListener('submit', formHandler);
            this.eventListeners.push({ element: form, event: 'submit', handler: formHandler });
        }
    }

    /**
     * Enter edit mode
     */
    enterEditMode() {
        const { viewState, editState } = this.elements;
        
        if (viewState && editState) {
            viewState.style.display = 'none';
            editState.style.display = 'block';
            this.connectionCard.classList.add('connection--editing');
            
            // Trigger custom event
            this.triggerEvent('connection:edit-mode-entered', { connectionId: this.connectionId });
        }
    }

    /**
     * Exit edit mode
     */
    exitEditMode() {
        const { viewState, editState } = this.elements;
        
        if (viewState && editState) {
            editState.style.display = 'none';
            viewState.style.display = 'block';
            this.connectionCard.classList.remove('connection--editing');
            
            // Trigger custom event
            this.triggerEvent('connection:edit-mode-exited', { connectionId: this.connectionId });
        }
    }

    /**
     * Handle connection deletion
     */
    handleDelete() {
        // Get confirmation message from data attribute or use default
        const confirmMessage = this.connectionCard.dataset.deleteConfirmMessage || 
                              'Are you sure you want to delete this connection?';
        
        if (confirm(confirmMessage)) {
            // Trigger custom event for deletion
            this.triggerEvent('connection:delete-requested', { 
                connectionId: this.connectionId,
                connectionCard: this.connectionCard 
            });
            
            console.log('Delete connection requested:', this.connectionId);
            
            // You can implement actual deletion logic here
            // or listen for the custom event elsewhere in your code
        }
    }

    /**
     * Handle connection save
     */
    handleSave() {
        // First, ensure the edit state is visible for validation
        const { viewState, editState } = this.elements;
        if (editState && editState.style.display === 'none') {
            // Temporarily show the edit state for validation
            const wasHidden = true;
            editState.style.display = 'block';
            if (viewState) viewState.style.display = 'none';
        }

        // Check form validity first
        const form = this.connectionCard.querySelector('form');
        if (form && !form.checkValidity()) {
            // If form is invalid, show validation messages and stop
            form.reportValidity();
            return;
        }

        // Collect form data
        const formData = this.collectFormData();
        
        // Trigger custom event for save
        this.triggerEvent('connection:save-requested', { 
            connectionId: this.connectionId,
            formData: formData,
            connectionCard: this.connectionCard 
        });
        
        console.log('Save connection requested:', this.connectionId, formData);
    }

    /**
     * Collect form data from the edit state
     * @returns {Object}
     */
    collectFormData() {
        const { editState } = this.elements;
        const formData = {};
        
        if (editState) {
            const inputs = editState.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                if (input.name) {
                    formData[input.name] = input.value;
                }
            });
        }
        
        return formData;
    }

    /**
     * Trigger a custom event
     * @param {string} eventName 
     * @param {Object} detail 
     */
    triggerEvent(eventName, detail = {}) {
        const event = new CustomEvent(eventName, {
            detail: detail,
            bubbles: true,
            cancelable: true
        });
        
        this.connectionCard.dispatchEvent(event);
    }

    /**
     * Clean up event listeners
     */
    destroy() {
        this.eventListeners.forEach(({ element, event, handler }) => {
            element.removeEventListener(event, handler);
        });
        this.eventListeners = [];
    }
}

/**
 * Initialize settings manager when DOM is ready
 */
document.addEventListener('DOMContentLoaded', function() {
    window.EcowittSettingsManager = new SettingsManager();
    
    // Example: Listen for custom events
    document.addEventListener('connection:delete-requested', function(e) {
        const { connectionId, connectionCard } = e.detail;
        
        // Implement your deletion logic here
        // This could involve AJAX calls to your backend
        console.log('Handling deletion for connection:', connectionId);
        
        // Example: Remove from DOM after successful deletion
        // connectionCard.remove();
        // window.EcowittSettingsManager.removeConnection(connectionId);
    });
    
    document.addEventListener('connection:save-requested', function(e) {
        const { connectionId, formData } = e.detail;
        
        // Check if this is a new connection (starts with 'new_')
        if (connectionId.startsWith('new_')) {
            // Handle new connection save
            window.EcowittSettingsManager.handleNewConnectionSave(connectionId, formData, e.detail.connectionCard);
        } else {
            // Handle existing connection save
            console.log('Handling save for existing connection:', connectionId, formData);
        }
    });
});

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    if (window.EcowittSettingsManager) {
        window.EcowittSettingsManager.destroy();
    }
});
