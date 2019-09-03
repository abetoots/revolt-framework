import React from 'react';
import './Job.scss';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import JobTag from '../../../components/UI/JobTag/JobTag';

const Job = (props) => {
    //Add the job type to be rendered with tags
    /**
     * We clone the tags array we receive to prevent pushing into
     * our tags array from the state
     */
    const tags = [...props.tags];
    tags.push(props.type)
    //Dynamic icons and classname depending if user is verified
    const verifiedClassnames = ["Job__verified", props.verified ? "-true" : "-false"];
    const icon = props.verified ? <FontAwesomeIcon icon={['fas', 'check']} /> : <FontAwesomeIcon icon={['fas', 'exclamation-circle']} />;
    return (
        <div className="Job">
            <div className="Job__photoBlock">
                {props.employerPhoto ? <img className="Job__employerPhoto" src={props.employerPhoto} alt="Employer" /> :
                    <FontAwesomeIcon className="Job__icon" icon={['fas', 'user']} size="2x" />
                }

            </div>
            <div className="Job__infoBlock">
                <h2 className="Job__title">{props.title}</h2>
                <div className="Job__employerMeta">
                    <span className="Job__location">{props.location}</span>
                    <div className={verifiedClassnames.join(' ')}>
                        <p className="Job__isVerified">Verified {icon}</p>
                    </div>
                </div>
                <h3 className="Job__employerName">{props.name}</h3>
            </div>
            <div className="Job__tagsBlock">
                {tags.map((tag, index) => (
                    <JobTag key={index} tag={tag} />
                ))}
            </div>
        </div>
    );
};

export default Job;