import React from 'react';
import './List.scss';

import ListOutput from './ListOutput/ListOutput';
import Spinner from '../UI/Spinner/Spinner';

const list = (props) => {
    let listOutput = props.error ? <p>{`${props.listGroup} could not be loaded`}</p> : <Spinner />;

    if (!props.loading) {
        if (props.listData.length !== 0) {
            listOutput = (
                props.listData.map((item, index) => (
                    <ListOutput
                        key={index}
                        group={props.listGroup}
                        data={item}
                    />
                ))
            );
        } else {
            switch (props.listGroup) {
                case 'Job Posts':
                    listOutput = <h6>You haven't posted a job yet</h6>;
                    break;
                case 'Saved Candidates':
                    listOutput = <h6>Browse and save candidates</h6>;
                    break;
                default: break;

            }
        }

    }
    return (
        <div className="List">
            <h3 className="List__heading">{props.listGroup}</h3>
            <div className="List__tabs">
                {props.tabs.map((tab, index) => (
                    <button className="List__tab" key={index}>{tab}</button>
                ))}
            </div>
            <div className="List__subheading">
                {props.subheading ? props.subheading : null}
            </div>
            <div className="List__output">
                {listOutput}
            </div>
            <div className="List__actionBlock">
                <button className="List__actionButton">{`View ${props.listGroup}`}</button>
            </div>

        </div>
    );
};

export default list;