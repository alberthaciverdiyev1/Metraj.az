let startTime = performance.now();
let completedRequests = 0;
let totalRequests = 0;
const loadTimerElem = document.getElementById('loadTimer');

if (loadTimerElem) {
    if (window.axios) {
        axios.interceptors.request.use(config => {
            totalRequests++;
            return config;
        });

        axios.interceptors.response.use(response => {
            completedRequests++;
            checkIfAllDone();
            return response;
        }, error => {
            completedRequests++;
            checkIfAllDone();
            return Promise.reject(error);
        });
    }

    const originalFetch = window.fetch;
    window.fetch = function (...args) {
        totalRequests++;
        return originalFetch(...args)
            .then(response => {
                completedRequests++;
                checkIfAllDone();
                return response;
            })
            .catch(err => {
                completedRequests++;
                checkIfAllDone();
                throw err;
            });
    };

    function checkIfAllDone() {
        if (completedRequests >= totalRequests) {
            const endTime = performance.now();
            const duration = ((endTime - startTime) / 1000).toFixed(2);
            document.getElementById('loadTimer').innerText = `Load Time: ${duration} s`;
        }
    }

    window.addEventListener('load', () => {
        setTimeout(() => {
            if (totalRequests === 0) {
                const endTime = performance.now();
                const duration = ((endTime - startTime) / 1000).toFixed(2);
                document.getElementById('loadTimer').innerText = `Load Time: ${duration} s`;
            }
        }, 100);
    });
}

