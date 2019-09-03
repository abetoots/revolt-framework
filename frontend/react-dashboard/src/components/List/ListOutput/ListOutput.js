import React from 'react';
import './ListOutput.css';

import Aux from '../../../hoc/Auxiliary';

import PhotoIcon from '../../UI/PhotoIcon/PhotoIcon';
import Stat from '../../UI/Stat/Stat';

const listOutput = (props) => {
    let listItems = '';
    switch (props.group) {
        case 'Saved Candidates':
            listItems =
                <div className="ListOutput Candidates">
                    <PhotoIcon src={props.data.photoSrc} alt={props.data.name} />
                    <div className="Candidate__nameBlock">
                        <h2 className="Candidate__fullName">{props.data.name}</h2>
                        <h3 className="Candidate__title">{props.data.title}</h3>
                    </div>
                </div>

            break;
        case 'Job Posts':
            listItems =
                <div className="ListOutput JobPosts">
                    <h2 className="Job__title">{props.data.title}</h2>
                    <Stat statName="Views" statCount="2" />
                    <Stat statName="Applications" statCount="3" />
                </div>

            break;
        default:
            return;
    }
    return (
        <Aux>
            {listItems}
        </Aux>

    );

};

export default listOutput;