import React from 'react';
import './Candidate.scss';

import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import PhotoIcon from '../../../../components/UI/PhotoIcon/PhotoIcon';
import JobTag from '../../../../components/UI/JobTag/JobTag';
import Available from '../../../../components/UI/Available/Available';

const candidate = (props) => {

    return (
        <div className="Candidate">
            <div className="Candidate__header">
                <PhotoIcon className="Candidate__photo" src={props.src} alt={props.fullName} />
                <div className="Candidate__nameBlock">
                    <h2 className="Candidate__fullName">{props.fullName}</h2>
                    <h3 className="Candidate__title">{props.title}</h3>
                </div>
            </div>
            <hr className="Candidate__horizontal" />
            <div className="Candidate__overviewBlock">
                {props.overview}
            </div>
            <div className="Candidate__skillsBlock">
                <h4>Skills:</h4>
                {props.skills.map((skill, index) => (
                    <JobTag key={index} tag={skill.name} />
                ))}
            </div>
            <div className="Candidate__salaryBlock">
                <h4>Expected Salary:</h4>
                <p className="Candidate__theSalary">
                    <strong>${props.salary}</strong> / month
                    </p>
            </div>
            <div className="Candidate__actionBlock">
                <FontAwesomeIcon className="Candidate__save" icon={['far', 'heart']} size="2x" />
                <FontAwesomeIcon className="Candidate__message" icon={['far', 'paper-plane']} size="2x" />
                <Available available={props.available} size="2x" />
            </div>
        </div>
    );
};

export default candidate;