import React, { Component } from 'react';
import './Candidates.scss';

import { connect } from 'react-redux';
import Spinner from '../../../components/UI/Spinner/Spinner';
import Candidate from './Candidate/Candidate';

import { fetchCandidates } from '../../../store/actions/index'

//TODO refactor with redux
class Candidates extends Component {

    componentDidMount() {
        this.props.fetchOnMount(this.props.token);
    }

    render() {

        //switch between error, spinner, or the candidates
        let renderedChild = this.props.error ? <p>It appears that the candidates cannot be loaded!</p> : <Spinner />;
        if (this.props.candidates.length !== 0) {
            renderedChild = (
                <div>
                    {this.props.candidates.map(candidate => {
                        return (
                            <Candidate key={candidate.id}
                                photoSrc={candidate.photoSrc}
                                fullName={candidate.name}
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
        }

        return (
            <div className="Candidates">
                {renderedChild}
            </div>
        );
    }
};

const mapStateToProps = state => {
    return {
        token: state.auth.token,
        candidates: state.candidates.candidates,
        error: state.candidates.error,
        loading: state.candidates.loading
    }
}

const mapDispatchToProps = dispatch => {
    return {
        fetchOnMount: (token) => dispatch(fetchCandidates(token))
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(Candidates);