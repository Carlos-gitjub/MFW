document.addEventListener('DOMContentLoaded', () => {
    const baseUrl = document.querySelector('.mfw-template-subscription-most')?.dataset.baseUrl || '/';
    const form = document.getElementById('tmdb-search-form');
    const input = document.getElementById('movie-title-input');
    const resultBox = document.getElementById('search-result');
    const movieList = document.getElementById('movie-list');
    const countDisplay = document.getElementById('movie-count');
    const analyzeBtn = document.getElementById('analyze-button');

    // Internal list of added movies
    let movieListState = [];

    // Handle TMDb search
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const title = input.value.trim();
        if (!title) return;

        resultBox.className = 'alert alert-info';
        resultBox.innerText = 'Searching...';

        const fetchUrl = baseUrl + 'where-to-watch/advanced/tmdb-lookup?title=' + encodeURIComponent(title);

        const res = await fetch(fetchUrl);
        const data = await res.json();

        if (!data.success) {
            resultBox.className = 'alert alert-danger';
            resultBox.innerText = '❌ Movie not found.';
            return;
        }

        const movie = data.movie;
        resultBox.className = 'alert alert-success';
        resultBox.innerHTML = `
            ✅ <strong>${movie.title}</strong> (${movie.year})
            <button class="btn btn-sm btn-outline-primary ms-3" id="add-movie-btn">Add to list</button>
        `;

        document.getElementById('add-movie-btn').addEventListener('click', () => {
            if (movieListState.length >= 30) {
                alert('You can add up to 30 movies only.');
                return;
            }

            if (movieListState.some(m => m.tmdb_id === movie.tmdb_id)) {
                alert('Movie already added.');
                return;
            }

            movieListState.push(movie);
            updateMovieList();
            resultBox.className = 'alert alert-secondary';
            resultBox.innerText = '✅ Added to list.';
            input.value = '';
            input.focus();
        });
    });

    // Update UI with current movie list
    function updateMovieList() {
        movieList.innerHTML = '';
        countDisplay.innerText = movieListState.length;

        movieListState.forEach((m, i) => {
            const li = document.createElement('li');
            li.className = 'list-group-item d-flex justify-content-between align-items-center';
            li.innerHTML = `<span><strong>${m.title}</strong> (${m.year})</span>
                            <button class="btn btn-sm btn-danger" onclick="removeMovie(${i})">Remove</button>`;
            movieList.appendChild(li);
        });

        // Mostrar u ocultar botón según cantidad de películas
        if (movieListState.length === 0) {
            analyzeBtn.classList.add('d-none');
        } else {
            analyzeBtn.classList.remove('d-none');
        }
    }

    // Remove movie from list
    window.removeMovie = (index) => {
        movieListState.splice(index, 1);
        updateMovieList();
    };

    // Submit movie list for analysis
    analyzeBtn.addEventListener('click', () => {
        const resultContainer = document.getElementById('subscription-result-container');
        resultContainer.innerHTML = '<div class="alert alert-info">🔄 Analyzing titles, please wait...</div>';
        const region = document.getElementById('regionInput').value;
        const newUrl = `${window.location.pathname}?region=${encodeURIComponent(region)}`;
        window.history.replaceState({}, '', newUrl);
        const csrfToken = document.querySelector('.mfw-template-subscription-most')?.dataset.csrfToken || '';
        fetch(baseUrl + 'where-to-watch/advanced/subscription-most/result?region=' + encodeURIComponent(region), {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                movies: movieListState,
                csrf_token: csrfToken
            })
        })
        .then(res => res.text())
        .then(html => {
            resultContainer.innerHTML = html;
        })
        .catch(() => {
            resultContainer.innerHTML = '<div class="alert alert-danger">❌ Error analyzing movie list.</div>';
        });
    });
    
});
