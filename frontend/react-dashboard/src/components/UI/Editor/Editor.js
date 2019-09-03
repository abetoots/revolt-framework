import React from 'react';

import FroalaEditor from 'react-froala-wysiwyg';
const editor = props => (
    /**
     * *Dont forget to handle the model when rendering this component
     */

    <div>
        <FroalaEditor
            config={props.config}
            model={props.model}
            onModelChange={props.changed}
        />
    </div>
);


export default editor;