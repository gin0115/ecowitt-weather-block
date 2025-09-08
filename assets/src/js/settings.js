/**
 * Settings JS
 * 
 * @package PinkCrab\Ecowitt_Weather_Block
 * @license GPL-3.0-or-later
 * @since 0.1.0
 */

// Simple functional approach for settings management
document.addEventListener('DOMContentLoaded', function() {
    // Use event delegation - single listener handles all clicks
    document.addEventListener('click', handleClick);
    
    // Watch for new connections being added
    const observer = new MutationObserver(handleNewConnections);
    observer.observe(document.body, { childList: true, subtree: true });
    
    // Initially disable new connection form fields since form is hidden
    enableNewConnectionFormFields(false);
});

// Constants for better maintainability
const SELECTORS = {
    settingsSection: '.ecowitt-settings-section',
    connectionCard: '[id^="connection-"]',
    connectionsContainer: '.connections-list, .ecowitt-connections',
    viewState: '.connection__view',
    editState: '.connection__edit',
    newConnectionForm: '#new-connection-form',
    newConnectionSection: '#new-connection'
};

/**
 * Handle all clicks via event delegation
 */
function handleClick(event) {
    // Prevent ANY button inside .ecowitt-settings-section from submitting forms
    if (event.target.tagName === 'BUTTON' && event.target.closest(SELECTORS.settingsSection)) {
        event.preventDefault();
    }
    
    const action = event.target.dataset.action;
    if (!action) return;
    
    switch (action) {
        case 'edit-connection':
            showEditMode(event.target);
            break;
        case 'cancel-edit':
            showViewMode(event.target);
            break;
        case 'delete-connection':
            deleteConnection(event.target);
            break;
        case 'show-new-connection-form':
            showNewConnectionForm();
            break;
        case 'save-connection':
            saveConnection(event.target);
            break;
    }
}

/**
 * Get form field values for a connection
 */
function getConnectionFormData(connection) {
    // Extract connection ID from element ID (connection-ecowitt -> ecowitt)
    const connectionId = connection.id.replace('connection-', '');
    
    // Helper function to find field by multiple possible name patterns
    const getFieldValue = (fieldPrefix) => {
        // Try exact match first
        let field = connection.querySelector(`[name="${fieldPrefix}[${connectionId}]"]`);
        if (field) return field.value;
        
        // Try partial match for cases where ID might have changed
        field = connection.querySelector(`[name*="${fieldPrefix}["]`);
        if (field) return field.value;
        
        return '';
    };
    
    // Get all form field values using exact field names
    return {
        connectionId: connectionId,
        name: getFieldValue('connection_name'),
        description: getFieldValue('connection_description'),
        applicationKey: getFieldValue('connection_application_key'),
        apiKey: getFieldValue('connection_api_key'),
        macAddress: getFieldValue('connection_mac_address')
    };
}

/**
 * Set form field values for a connection
 */
function setConnectionFormData(connection, data) {
    const connectionId = data.connectionId;
    
    // Helper function to find and set field value
    const setFieldValue = (fieldPrefix, value) => {
        // Try exact match first
        let field = connection.querySelector(`[name="${fieldPrefix}[${connectionId}]"]`);
        if (!field) {
            // Try partial match for cases where ID might have changed
            field = connection.querySelector(`[name*="${fieldPrefix}["]`);
        }
        if (field) {
            field.value = value || '';
        }
    };
    
    setFieldValue('connection_name', data.name);
    setFieldValue('connection_description', data.description);
    setFieldValue('connection_application_key', data.applicationKey);
    setFieldValue('connection_api_key', data.apiKey);
    setFieldValue('connection_mac_address', data.macAddress);
}

/**
 * Show edit mode for a connection
 */
function showEditMode(button) {
    const connection = button.closest(SELECTORS.connectionCard);
    if (!connection) return;
    
    // Cache the current form values before editing
    connection._cachedData = getConnectionFormData(connection);
    
    toggleConnectionState(connection, 'edit');
}

/**
 * Show view mode for a connection
 */
function showViewMode(button) {
    const connection = button.closest(SELECTORS.connectionCard);
    if (!connection) return;
    
    // Restore cached values on cancel
    if (connection._cachedData) {
        setConnectionFormData(connection, connection._cachedData);
        console.log('Restored cached data for:', connection.id);
        delete connection._cachedData;
    }
    
    toggleConnectionState(connection, 'view');
    
    // Handle cancel on new connection form
    const newConnectionForm = document.getElementById('new-connection-form');
    const newConnectionSection = document.getElementById('new-connection');
    
    if (button.closest('#new-connection-form') && newConnectionForm && newConnectionSection) {
        newConnectionForm.style.display = 'none';
        newConnectionSection.style.display = 'block';
        newConnectionForm.querySelector('form')?.reset();
        
        // Disable form fields so they won't be submitted
        enableNewConnectionFormFields(false);
    }
}

/**
 * Toggle between view and edit states
 */
function toggleConnectionState(connection, state) {
    const viewState = connection.querySelector(SELECTORS.viewState);
    const editState = connection.querySelector(SELECTORS.editState);
    
    if (!viewState || !editState) return;
    
    if (state === 'edit') {
        viewState.style.display = 'none';
        editState.style.display = 'block';
        connection.classList.add('connection--editing');
    } else {
        editState.style.display = 'none';
        viewState.style.display = 'block';
        connection.classList.remove('connection--editing');
    }
}

/**
 * Delete connection with confirmation
 */
function deleteConnection(button) {
    const connection = button.closest(SELECTORS.connectionCard);
    if (!connection) return;
    
    if (confirm('Are you sure you want to delete this connection?')) {
        console.log('Connection deleted:', connection.id);
        connection.remove();
    }
}

/**
 * Show new connection form
 */
function showNewConnectionForm() {
    const newConnectionSection = document.querySelector(SELECTORS.newConnectionSection);
    const newConnectionForm = document.querySelector(SELECTORS.newConnectionForm);
    
    if (newConnectionSection && newConnectionForm) {
        newConnectionSection.style.display = 'none';
        newConnectionForm.style.display = 'block';
        
        // Show the edit section
        const editSection = newConnectionForm.querySelector(SELECTORS.editState);
        if (editSection) {
            editSection.style.display = 'block';
        }
        
        // Enable form fields so they can be used for creating new connections
        enableNewConnectionFormFields(true);
    }
}

/**
 * Update view elements with form data
 */
function updateConnectionView(connection, formData) {
    const viewState = connection.querySelector('.connection__view');
    if (!viewState) return;
    
    // Update connection title
    const titleElement = viewState.querySelector('.connection__title');
    if (titleElement) {
        titleElement.textContent = formData.name || 'Unnamed Connection';
    }
    
    // Update description
    const descriptionElement = viewState.querySelector('.connection__description');
    if (formData.description) {
        if (descriptionElement) {
            descriptionElement.textContent = formData.description;
            descriptionElement.style.display = 'block';
        } else {
            // Create description element if it doesn't exist
            const content = viewState.querySelector('.connection__content');
            if (content) {
                const newDesc = document.createElement('p');
                newDesc.className = 'connection__description';
                newDesc.textContent = formData.description;
                content.insertBefore(newDesc, content.querySelector('.connection__details'));
            }
        }
    } else if (descriptionElement) {
        descriptionElement.style.display = 'none';
    }
    
    // Update API Key (masked)
    const apiKeyElement = viewState.querySelector('.connection__details .detail__value--masked[title*="••••"]');
    if (apiKeyElement && formData.apiKey) {
        const maskedKey = formData.apiKey.substring(0, 4) + '••••••••';
        apiKeyElement.textContent = maskedKey;
        apiKeyElement.title = maskedKey;
    }
    
    // Update API Secret (masked) - find the second masked element
    const maskedElements = viewState.querySelectorAll('.detail__value--masked');
    if (maskedElements.length > 1 && formData.apiSecret) {
        const maskedSecret = formData.apiSecret.substring(0, 4) + '••••••••';
        maskedElements[1].textContent = maskedSecret;
        maskedElements[1].title = maskedSecret;
    }
    
    // Update MAC Address
    const macElement = viewState.querySelector('.detail__value:not(.detail__value--masked)');
    if (macElement && formData.macAddress) {
        macElement.textContent = formData.macAddress;
    }
    
    // Update connection status if all required fields are filled
    const statusElement = viewState.querySelector('.connection__status');
    if (statusElement) {
        const isActive = formData.name && formData.apiKey && formData.apiSecret && formData.macAddress;
        statusElement.textContent = isActive ? 'Connected' : 'Not Configured';
        
        // Update connection card class
        connection.classList.remove('connection--active', 'connection--inactive');
        connection.classList.add(isActive ? 'connection--active' : 'connection--inactive');
    }
}

/**
 * Save connection
 */
function saveConnection(button) {
    const connection = button.closest(SELECTORS.connectionCard);
    if (!connection) return;
    
    // Check if this is a new connection (either __new_ or connection-__new_)
    if (connection.id === 'connection-__new_' || connection.id.includes('__new_')) {
        createNewConnection(connection);
        return;
    }
    
    // Handle existing connection update
    const formData = getConnectionFormData(connection);
    console.log('Saving connection:', connection.id, formData);
    
    // Update the view with new values
    updateConnectionView(connection, formData);
    
    // Clean up cached data since we're saving
    delete connection._cachedData;
    
    // Switch back to view mode
    toggleConnectionState(connection, 'view');
}

/**
 * Create a new connection from the template
 */
function createNewConnection(newConnectionElement) {
    // Generate unique ID with better uniqueness guarantee
    const timestamp = Date.now();
    const random = Math.floor(Math.random() * 1000000); // 0-999999
    const uniqueId = `__new_${timestamp}_${random}`;
    
    console.log('Creating new connection with ID:', uniqueId);
    
    // Get form data from the new connection form - be more flexible in finding fields
    const getFieldValue = (fieldPattern) => {
        const field = newConnectionElement.querySelector(`[name*="${fieldPattern}"]`);
        return field ? field.value : '';
    };
    
    const formData = {
        name: getFieldValue('connection_name'),
        description: getFieldValue('connection_description'),
        applicationKey: getFieldValue('connection_application_key'),
        apiKey: getFieldValue('connection_api_key'),
        macAddress: getFieldValue('connection_mac_address')
    };
    
    console.log('Form data:', formData);
    
    // Validate required fields
    if (!formData.name || !formData.applicationKey || !formData.apiKey || !formData.macAddress) {
        alert('Please fill in all required fields (Name, Application Key, API Key, and MAC Address)');
        return;
    }
    
    // Get the connection template from localized data
    if (!window.ecowittSettings?.connectionTemplate) {
        console.error('Connection template not available');
        return;
    }
    
    // Replace template placeholders with actual values
    let connectionHtml = window.ecowittSettings.connectionTemplate
        .replace(/{key}/g, uniqueId)
        .replace(/{name}/g, formData.name || 'Unnamed Connection')
        .replace(/{description}/g, formData.description || '')
        .replace(/{application_key}/g, formData.applicationKey)
        .replace(/{api_key}/g, formData.apiKey)
        .replace(/{mac_address}/g, formData.macAddress);
    
    // Create masked versions for display
    const maskedApplicationKey = formData.applicationKey ? formData.applicationKey.substring(0, 4) + '••••••••' : '';
    const maskedApiKey = formData.apiKey ? formData.apiKey.substring(0, 4) + '••••••••' : '';
    
    // Replace masked placeholders
    connectionHtml = connectionHtml.replace(/{application_key_masked}/g, maskedApplicationKey);
    connectionHtml = connectionHtml.replace(/{api_key_masked}/g, maskedApiKey);
    
    // Find the connections container and add the new connection
    const connectionsContainer = document.querySelector(SELECTORS.connectionsContainer);
    if (!connectionsContainer) {
        console.error('Connections container not found');
        return;
    }
    
    // Create and add the new connection
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = connectionHtml;
    const newConnectionCard = tempDiv.firstElementChild;
    
    // Ensure the connection has the correct ID
    newConnectionCard.id = `connection-${uniqueId}`;
    
    // Update all form field names to use the new unique ID
    updateConnectionFieldNames(newConnectionCard, uniqueId);
    
    // Add to the connections container
    connectionsContainer.appendChild(newConnectionCard);
    
    // Update the connection view with the form data
    updateConnectionView(newConnectionCard, formData);
    
    // Ensure the connection is in view mode (not edit mode)
    toggleConnectionState(newConnectionCard, 'view');
    
    // Hide the new connection form and show the "Add New" button
    const newConnectionForm = document.querySelector(SELECTORS.newConnectionForm);
    const newConnectionSection = document.querySelector(SELECTORS.newConnectionSection);
    
    if (newConnectionForm && newConnectionSection) {
        newConnectionForm.style.display = 'none';
        newConnectionSection.style.display = 'block';
        newConnectionForm.querySelector('form')?.reset();
        
        // Disable form fields so they won't be submitted
        enableNewConnectionFormFields(false);
    }
    
    console.log('New connection created with ID:', uniqueId);
}

/**
 * Update all form field names in a connection to use the correct unique ID
 */
function updateConnectionFieldNames(connectionElement, uniqueId) {
    const fieldsToUpdate = [
        'connection_key',
        'connection_name', 
        'connection_description',
        'connection_application_key',
        'connection_api_key',
        'connection_mac_address'
    ];
    
    fieldsToUpdate.forEach(fieldName => {
        const field = connectionElement.querySelector(`[name*="${fieldName}"]`);
        if (field) {
            field.name = `${fieldName}[${uniqueId}]`;
            
            // Remove any validation attributes that might cause issues
            field.removeAttribute('required');
            field.removeAttribute('pattern');
            
            console.log(`Updated field name to: ${field.name}`);
        }
    });
}/**
 * Enable or disable new connection form fields
 */
function enableNewConnectionFormFields(enable) {
    const newConnectionForm = document.querySelector(SELECTORS.newConnectionForm);
    if (!newConnectionForm) return;
    
    // Find all form fields in the new connection form
    const fields = newConnectionForm.querySelectorAll('input[name*="[__new_]"]');
    
    fields.forEach(field => {
        if (enable) {
            field.removeAttribute('disabled');
        } else {
            field.setAttribute('disabled', 'disabled');
        }
    });
    
    console.log(`${enable ? 'Enabled' : 'Disabled'} ${fields.length} new connection form fields`);
}

/**
 * Handle new connections being added to DOM
 */
function handleNewConnections(mutations) {
    mutations.forEach(mutation => {
        mutation.addedNodes.forEach(node => {
            if (node.nodeType === Node.ELEMENT_NODE && node.matches && node.matches('[id^="connection-"]')) {
                // New connection added - no extra setup needed with event delegation
                console.log('New connection detected:', node.id);
            }
        });
    });
}
