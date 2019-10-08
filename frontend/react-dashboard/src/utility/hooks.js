import { useState, useEffect } from 'react';

//init cache for our scripts
const scriptsCache = new Set();
export function useScript(src) {
    // Keeping track of script loaded and error state
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(false);
    const [done, setDone] = useState(false);

    useEffect(
        () => {
            // If scriptsCache array already includes src that means another instance ...
            // ... of this hook already loaded this script, so no need to load again.
            if (scriptsCache.has(src)) {
                console.log('cache has src');
                setLoading(false);
                setDone(true);
                setError(false);
            } else {
                console.log('cache doesnt have src');
                scriptsCache.add(src);
            }

            console.log(scriptsCache);

            // Create script
            let script = document.createElement('script');
            script.src = src;
            script.async = true;

            // Script event listener callbacks for load and error
            const onScriptLoad = () => {
                console.log('loaded');
                setLoading(false);
                setDone(true);
                setError(false);
            };

            const onScriptError = () => {
                // Remove from scriptsCache we can try loading again
                scriptsCache.delete(src);
                script.remove();
                setLoading(false);
                setDone(true);
                setError(true);
            };

            script.addEventListener('load', onScriptLoad);
            script.addEventListener('error', onScriptError);

            // Add script to document body
            document.body.appendChild(script);

            // Remove event listeners on cleanup
            return () => {
                script.removeEventListener('load', onScriptLoad);
                script.removeEventListener('error', onScriptError);
            };

        },
        [src] // Only re-run effect if script src changes
    );

    return [loading, done, error];
}