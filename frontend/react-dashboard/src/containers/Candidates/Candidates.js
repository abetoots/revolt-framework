import React, { Component } from 'react';
import './Candidates.scss';

import { connect } from 'react-redux';
import Spinner from '../../components/UI/Spinner/Spinner';
import Candidate from './Candidate/Candidate';

import { fetchCandidates } from '../../store/actions/index'

class Candidates extends Component {

    componentDidMount() {
        if (!this.props.loaded) {
            this.props.fetchCandidatesOnMount(this.props.token);
        }
    }

    render() {

        //switch between error, spinner, or the candidates
        let renderedChild = this.props.error ? <p>It appears that the candidates cannot be loaded!</p> : <Spinner />;
        if (this.props.loaded) {
            if (this.props.candidates.length !== 0) {
                renderedChild = (
                    <div>
                        {this.props.candidates.map(candidate => {
                            return (
                                <Candidate
                                    key={candidate.id}
                                    photoSrc={candidate.photoSrc}
                                    name={candidate.name}
                                    title={candidate.title}
                                    overview={candidate.overview}
                                    skills={candidate.skills}
                                    available={candidate.availability}
                                    salary={candidate.salary}
                                />
                            );
                        })}
                    </div>
                );
            } else {
                renderedChild = <p>No candidates available.</p>
            }
        }

        return (
            <div className={this.props.loaded ? "Candidates" : ""}>
                {renderedChild}
            </div>
        );
    }
};

const mapStateToProps = state => {
    return {
        token: state.auth.token,
        candidates: state.candidates.candidates,
        error: state.candidates.errorCandidates,
        loaded: state.candidates.loadedCandidates
    }
}

const mapDispatchToProps = dispatch => {
    return {
        fetchCandidatesOnMount: (token) => dispatch(fetchCandidates(token))
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(Candidates);