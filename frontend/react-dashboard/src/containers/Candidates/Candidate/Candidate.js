import React from 'react';
import './Candidate.scss';

import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import PhotoIcon from '../../../components/UI/PhotoIcon/PhotoIcon';
import JobTag from '../../../components/UI/JobTag/JobTag';
import Available from '../../../components/UI/Available/Available';
import Line from '../../../components/UI/Line/Line';

const candidate = (props) => {

    return (
        <div className="Candidate">
            <div className="Candidate__header">
                <PhotoIcon className="Candidate__photo" src={props.photoSrc} alt={props.name} />
                <div className="Candidate__block -name">
                    <h2 className="Candidate__name">{props.name}</h2>
                    <h3 className="Candidate__title">{props.title}</h3>
                </div>
            </div>
            <Line />
            <div className="Candidate__block -overview">
                {props.overview}
            </div>
            <div className="Candidate__block -skills">
                <h4 className="Candidate__blockHeading">Skills:</h4>
                {props.skills.map((skill, index) => (
                    <JobTag key={index} tag={skill.name} />
                ))}
            </div>
            <div className="Candidate__block -salary">
                <h4 className="Candidate__blockHeading">Expected Salary:</h4>
                <p className="Candidate__salary">
                    <strong>${props.salary}</strong> / month
                    </p>
            </div>
            <div className="Candidate__block -action">
                <FontAwesomeIcon className="Candidate__save" icon={['far', 'heart']} size="2x" />
                <FontAwesomeIcon className="Candidate__message" icon={['far', 'paper-plane']} size="2x" />
                <Available available={props.available} size="2x" />
            </div>
        </div>
    );
};

export default candidate;