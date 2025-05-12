/**
 * Recipe Importer admin JavaScript
 */
(function($) {
    'use strict';

    // DOM ready
    $(function() {
        const importBtn = $('#import-recipes');
        const apiUrlInput = $('#api_url');
        const apiKeyInput = $('#api_key');
        const updateExistingCheckbox = $('#update_existing');
        const importResults = $('#import-results');
        const importLog = $('.import-log');
        const progressFill = $('.progress-fill');
        const progressText = $('.progress-text');

        // Import button click handler
        importBtn.on('click', function(e) {
            e.preventDefault();
            
            const apiUrl = apiUrlInput.val().trim();
            if (!apiUrl) {
                alert('Please enter a valid API URL.');
                return;
            }
            
            // Clear previous results and show results container
            importLog.empty();
            importResults.show();
            progressFill.css('width', '0%');
            progressText.text('0%');
            
            // Disable button during import
            importBtn.prop('disabled', true).text('Importing...');
            
            // Add initial log entry
            addLogEntry('Starting import from: ' + apiUrl, 'info');
            
            // Start the import process
            importRecipes(apiUrl);
        });
        
        /**
         * Import recipes from API
         * 
         * @param {string} apiUrl The API URL to fetch recipes from
         */
        function importRecipes(apiUrl) {
            progressFill.css('width', '10%');
            progressText.text('10%');
            
            // Get API key value
            const apiKey = apiKeyInput.val().trim();
            
            // AJAX request to server
            $.ajax({
                url: recipeImporterData.ajaxUrl,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'import_recipes',
                    nonce: recipeImporterData.nonce,
                    api_url: apiUrl,
                    api_key: apiKey,
                    update_existing: updateExistingCheckbox.is(':checked')
                },
                success: function(response) {
                    progressFill.css('width', '100%');
                    progressText.text('100%');
                    
                    if (response.success) {
                        const data = response.data;
                        
                        // Add log entries
                        if (data.log && data.log.length) {
                            for (let i = 0; i < data.log.length; i++) {
                                const message = data.log[i];
                                let type = 'info';
                                
                                if (message.indexOf('Created:') === 0) {
                                    type = 'created';
                                } else if (message.indexOf('Updated:') === 0) {
                                    type = 'updated';
                                } else if (message.indexOf('Skipped:') === 0) {
                                    type = 'skipped';
                                } else if (message.indexOf('Failed:') === 0) {
                                    type = 'failed';
                                }
                                
                                addLogEntry(message, type);
                            }
                        }
                        
                        // Add summary
                        addSummary({
                            created: data.created || 0,
                            updated: data.updated || 0,
                            skipped: data.skipped || 0,
                            failed: data.failed || 0
                        });
                        
                        addLogEntry('Import completed successfully!', 'info');
                    } else {
                        addLogEntry('Error: ' + (response.data.message || 'Unknown error occurred.'), 'failed');
                    }
                    
                    // Re-enable the button
                    importBtn.prop('disabled', false).text('Import Recipes');
                },
                error: function(xhr, status, error) {
                    progressFill.css('width', '100%');
                    progressText.text('Error');
                    
                    addLogEntry('AJAX Error: ' + error, 'failed');
                    
                    // Re-enable the button
                    importBtn.prop('disabled', false).text('Import Recipes');
                }
            });
        }
        
        /**
         * Add entry to import log
         * 
         * @param {string} message The message to add
         * @param {string} type The entry type (info, created, updated, skipped, failed)
         */
        function addLogEntry(message, type) {
            const entry = $('<div class="log-entry"></div>')
                .addClass(type)
                .text(message);
                
            importLog.append(entry);
            
            // Scroll to bottom
            importLog.scrollTop(importLog[0].scrollHeight);
        }
        
        /**
         * Add import summary
         * 
         * @param {Object} stats The import statistics
         */
        function addSummary(stats) {
            // Check if summary already exists
            if ($('.import-summary').length) {
                $('.import-summary').remove();
            }
            
            const summary = $('<div class="import-summary"></div>');
            
            // Created
            const createdItem = $('<div class="summary-item created"></div>');
            createdItem.append($('<div class="summary-count"></div>').text(stats.created));
            createdItem.append($('<div class="summary-label"></div>').text('Created'));
            summary.append(createdItem);
            
            // Updated
            const updatedItem = $('<div class="summary-item updated"></div>');
            updatedItem.append($('<div class="summary-count"></div>').text(stats.updated));
            updatedItem.append($('<div class="summary-label"></div>').text('Updated'));
            summary.append(updatedItem);
            
            // Skipped
            const skippedItem = $('<div class="summary-item skipped"></div>');
            skippedItem.append($('<div class="summary-count"></div>').text(stats.skipped));
            skippedItem.append($('<div class="summary-label"></div>').text('Skipped'));
            summary.append(skippedItem);
            
            // Failed
            const failedItem = $('<div class="summary-item failed"></div>');
            failedItem.append($('<div class="summary-count"></div>').text(stats.failed));
            failedItem.append($('<div class="summary-label"></div>').text('Failed'));
            summary.append(failedItem);
            
            importResults.append(summary);
        }
    });
})(jQuery); 