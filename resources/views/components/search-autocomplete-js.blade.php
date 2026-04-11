@props([
    'inputId',
    'isAdmin' => false
])

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('{{ $inputId }}');
    if (!searchInput) return;

    // Create container for dropdown
    const dropdownContainer = document.createElement('div');
    dropdownContainer.id = '{{ $inputId }}-autocomplete-dropdown';
    dropdownContainer.className = 'absolute z-50 w-full bg-white mt-1 rounded-xl shadow-xl border border-gray-200 overflow-hidden hidden flex-col max-h-[400px] overflow-y-auto';
    
    // We assume the input is wrapped in a relative container. If not, this might behave weirdly.
    // It's best if the parent of the input has 'relative' class.
    searchInput.parentNode.classList.add('relative');
    searchInput.parentNode.appendChild(dropdownContainer);

    let debounceTimer;
    let selectedIndex = -1;
    let currentItems = [];

    searchInput.addEventListener('input', function(e) {
        const query = e.target.value.trim();
        
        clearTimeout(debounceTimer);
        
        if (query.length < 2) {
            hideDropdown();
            return;
        }

        // Show loading state
        showDropdown('<div class="p-4 text-center text-sm text-gray-500 flex items-center justify-center gap-2"><svg class="animate-spin h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Searching...</div>');

        debounceTimer = setTimeout(() => {
            fetchResults(query);
        }, 300);
    });

    searchInput.addEventListener('keydown', function(e) {
        if (!dropdownContainer.classList.contains('hidden') && currentItems.length > 0) {
            const itemElements = dropdownContainer.querySelectorAll('.autocomplete-item');
            
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                selectedIndex = (selectedIndex + 1) % itemElements.length;
                updateSelection(itemElements);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                selectedIndex = (selectedIndex - 1 + itemElements.length) % itemElements.length;
                updateSelection(itemElements);
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (selectedIndex >= 0 && selectedIndex < itemElements.length) {
                    itemElements[selectedIndex].click();
                } else if (currentItems.length > 0) {
                    itemElements[0].click(); // default to first
                }
            } else if (e.key === 'Escape') {
                e.preventDefault();
                hideDropdown();
            }
        }
    });

    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !dropdownContainer.contains(e.target)) {
            hideDropdown();
        }
    });

    function fetchResults(query) {
        const url = new URL('{{ route("search.suggestions") }}');
        url.searchParams.append('q', query);
        @if($isAdmin)
        url.searchParams.append('admin', 'true');
        @endif

        fetch(url)
            .then(res => res.json())
            .then(data => {
                renderResults(data, query);
            })
            .catch(err => {
                console.error('Search error:', err);
                showDropdown('<div class="p-4 text-center text-sm text-red-500">Error fetching results.</div>');
            });
    }

    function renderResults(data, query) {
        currentItems = [];
        let html = '';
        
        let hasResults = data.ebooks.length > 0 || data.authors.length > 0 || data.categories.length > 0 || data.tags.length > 0;

        if (!hasResults) {
            showDropdown(`<div class="p-5 text-center text-sm text-gray-500">
                <svg class="w-8 h-8 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                No results found for '<span class="font-semibold text-gray-700">${query}</span>'
            </div>`);
            return;
        }

        // Render Books
        if (data.ebooks.length > 0) {
            html += `<div class="px-3 py-1.5 bg-gray-50/80 border-y border-gray-100 text-[10px] font-bold text-gray-500 uppercase tracking-wider sticky top-0 backdrop-blur-sm z-10">Books</div>`;
            data.ebooks.forEach(book => {
                currentItems.push(book);
                const coverStyle = book.cover 
                    ? `<img src="${book.cover}" class="w-8 h-10 object-cover rounded shadow-sm flex-shrink-0" alt="Cover">` 
                    : `<div class="w-8 h-10 bg-blue-50 text-blue-300 rounded flex items-center justify-center flex-shrink-0"><svg class="w-4 h-4 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13"/></svg></div>`;
                
                const badge = book.access_level === 'premium' ? `<span class="ml-1 text-[9px] bg-purple-100 text-purple-700 px-1.5 py-0.5 rounded font-bold">Premium</span>` : '';

                html += `<a href="${book.url}" class="autocomplete-item flex items-center gap-3 px-4 py-2 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0 outline-none focus:bg-blue-50">
                    ${coverStyle}
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-semibold text-gray-900 truncate">${book.title}${badge}</div>
                    </div>
                </a>`;
            });
        }

        // Render Authors
        if (data.authors.length > 0) {
            html += `<div class="px-3 py-1.5 bg-gray-50/80 border-y border-gray-100 text-[10px] font-bold text-gray-500 uppercase tracking-wider sticky top-0 backdrop-blur-sm z-10">Authors</div>`;
            data.authors.forEach(author => {
                currentItems.push(author);
                html += `<a href="${author.url}" class="autocomplete-item flex items-center gap-3 px-4 py-2 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0 outline-none focus:bg-blue-50">
                    <div class="w-7 h-7 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center flex-shrink-0"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg></div>
                    <div class="text-sm font-medium text-gray-800 truncate">${author.full_name}</div>
                </a>`;
            });
        }

        // Render Categories
        if (data.categories.length > 0) {
            html += `<div class="px-3 py-1.5 bg-gray-50/80 border-y border-gray-100 text-[10px] font-bold text-gray-500 uppercase tracking-wider sticky top-0 backdrop-blur-sm z-10">Categories</div>`;
            data.categories.forEach(cat => {
                currentItems.push(cat);
                html += `<a href="${cat.url}" class="autocomplete-item flex items-center gap-3 px-4 py-2 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0 outline-none focus:bg-blue-50">
                    <div class="w-7 h-7 bg-amber-50 text-amber-600 rounded-lg flex items-center justify-center flex-shrink-0"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg></div>
                    <div class="text-sm font-medium text-gray-800">${cat.name}</div>
                </a>`;
            });
        }

        // Render Tags
        if (data.tags.length > 0) {
            html += `<div class="px-3 py-1.5 bg-gray-50/80 border-y border-gray-100 text-[10px] font-bold text-gray-500 uppercase tracking-wider sticky top-0 backdrop-blur-sm z-10">Tags</div><div class="px-4 py-3 flex flex-wrap gap-2">`;
            data.tags.forEach(tag => {
                currentItems.push(tag);
                html += `<a href="${tag.url}" class="autocomplete-item inline-block bg-teal-50 text-teal-700 hover:bg-teal-100 border border-teal-100 px-2 py-1 rounded text-xs font-semibold transition-colors outline-none focus:bg-teal-200">
                    #${tag.tag_name}
                </a>`;
            });
            html += `</div>`;
        }

        showDropdown(html);
        selectedIndex = -1; // reset selection
    }

    function updateSelection(elements) {
        elements.forEach((el, index) => {
            if (index === selectedIndex) {
                el.classList.add('bg-blue-50');
                if(el.scrollIntoViewIfNeeded) {
                    el.scrollIntoViewIfNeeded(false);
                } else if(el.scrollIntoView) {
                    el.scrollIntoView({ block: "nearest" });
                }
            } else {
                el.classList.remove('bg-blue-50');
            }
        });
    }

    function showDropdown(html) {
        dropdownContainer.innerHTML = html;
        dropdownContainer.classList.remove('hidden');
        dropdownContainer.classList.add('flex');
    }

    function hideDropdown() {
        dropdownContainer.classList.add('hidden');
        dropdownContainer.classList.remove('flex');
        selectedIndex = -1;
    }
});
</script>
