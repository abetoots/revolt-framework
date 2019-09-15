import React from 'react';
import './Job.scss';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import JobTag from '../../../components/UI/JobTag/JobTag';

const Job = (props) => {
    //Dynamic icons and classname depending if user is verified
    const verifiedClassnames = ["Job__verified", props.verified ? "-true" : "-false"];
    const icon = props.verified ? '✔️' : '❌';
    return (
        <div className="Job">
            <div className="Job__photoBlock">
                {props.authorPhoto ? <img className="Job__authorPhoto" src={props.authorPhoto} alt="Employer" /> :
                    <FontAwesomeIcon className="Job__icon" icon={['fas', 'user']} size="2x" />
                }

            </div>
            <div className="Job__infoBlock">
                <h2 className="Job__title">{props.title}</h2>
                <div className="Job__employerMeta">
                    <span className="Job__availability">{props.availability}</span>
                    <div className={verifiedClassnames.join(' ')}>
                        <p className="Job__isVerified">Verified <span role="img" aria-label="verified-icon">{icon}</span></p>
                    </div>
                </div>
                <h3 className="Job__author">{props.author}</h3>
            </div>
            <div className="Job__tagsBlock">
                {props.tags.map(tag => (
                    <JobTag key={tag} tag={tag} />
                ))}
            </div>
        </div>
    );
};

export default Job;