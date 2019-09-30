This project was bootstrapped with [Create React App](https://github.com/facebook/create-react-app).

#### Minor detail: why this app was created
Instead of settling for a *lot* of small react projects, I figured that a few well-done deployable apps would be better. The problem was the only back-end I was "familiar" with was WordPress. So, why not embed it inside? Hence, this project.

## Implementation
+ 📁 App logic is split into two folders *components* and *containers*, separating 'presentational' components from 'stateful' components. 

+ Redux for easily sharing state across components.

+ Redux thunk as middleware to dispatch asynchronous actions.

+ Routing

+ SCSS for my stylesheets using the BEM methodology.

+ 📌 Forms are built on top of ACF's local JSON to make sure we are synced. We take advantage of the JSON by importing them to state. We then invoke recursive functions to:

   - Add our own additional properties to the JSON structure
   - When editing a single job, we fetch it's data then have to map them properly to our nested state
   - When mapping each fields and nested sub_fields as components 
