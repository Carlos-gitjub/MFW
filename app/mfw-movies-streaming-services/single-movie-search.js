document.addEventListener('DOMContentLoaded', () => {
    const submitButton = document.getElementById('movie-search-button');
    const container = document.querySelector('.mfw-streaming-container');
    
    if (!submitButton || !container) return;
    
    submitButton.addEventListener('click', () => {
        // Clear any existing results or alerts
        const existingResults = container.querySelector('.result, .alert');
        if (existingResults) {
            existingResults.remove();
        }
        
        // Create and show loading message
        const loadingDiv = document.createElement('div');
        loadingDiv.className = 'alert alert-info';
        loadingDiv.innerHTML = '🔄 Analyzing title, please wait...';
        
        const form = submitButton.closest('form');
        form.parentNode.insertBefore(loadingDiv, form.nextSibling);
    });
});
