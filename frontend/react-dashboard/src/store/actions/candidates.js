import * as actionTypes from './actionTypes';
import axios from '../../axios-instance';

export const fetchCandidatesStart = () => {
    return {
        type: actionTypes.FETCH_CANDIDATES_START
    }
}

export const fetchCandidatesSuccess = candidatesData => {
    return {
        type: actionTypes.FETCH_CANDIDATES_SUCCESS,
        candidates: candidatesData
    }
}

export const fetchCandidatesFailed = error => {
    return {
        type: actionTypes.FETCH_CANDIDATES_FAILED,
        error: error
    }
}

export const fetchCandidates = token => {
    return dispatch => {
        dispatch(fetchCandidatesStart());
        axios.get('/wp-json/wp/v2/users?roles=jobseeker', { headers: { "Authorization": "Bearer " + token } })
            .then(response => {
                const fetchedCandidates = response.data.map(candidate => {
                    return {
                        id: candidate.id,
                        name: candidate.revolt_settings.revolt_js_name,
                        title: candidate.revolt_settings.revolt_js_title,
                        photoSrc: candidate.revolt_settings.revolt_js_photo,
                        overview: candidate.revolt_settings.revolt_js_overview,
                        website: candidate.revolt_settings.revolt_js_website,
                        salary: candidate.revolt_settings.revolt_js_salary,
                        skills: candidate.revolt_settings.revolt_js_skills,
                        availability: candidate.revolt_settings.revolt_js_availability,
                        experience: candidate.revolt_settings.revolt_js_experience,
                        portfolio: candidate.revolt_settings.revolt_js_portfolio
                    };
                });
                dispatch(fetchCandidatesSuccess(fetchedCandidates));
            })
            .catch(error => {
                console.log(error);
                dispatch(fetchCandidatesFailed(error));
            })
    }
};

export const fetchSavedCandidatesStart = () => {
    return {
        type: actionTypes.FETCH_SAVED_CANDIDATES_START
    }
};

export const fetchSavedCandidatesSuccess = savedCandidatesData => {
    return {
        type: actionTypes.FETCH_SAVED_CANDIDATES_SUCCESS,
        savedCandidates: savedCandidatesData
    }
};

export const fetchSavedCandidatesFailed = error => {
    return {
        type: actionTypes.FETCH_SAVED_CANDIDATES_FAILED,
        error: error
    }
};

export const fetchSavedCandidates = candidatesArray => {
    return dispatch => {
        dispatch(fetchSavedCandidatesStart());
        if (Array.isArray(candidatesArray)) {
            let urlString = '/wp-json/wp/v2/users?include=';
            for (let num of candidatesArray) {
                urlString += `${num},`;
            }
            axios.get(urlString)
                .then(response => {
                    console.log(response);
                    const savedCandidates = response.data.map(candidate => {
                        return {
                            id: candidate.id,
                            name: candidate.revolt_settings.revolt_js_name,
                            title: candidate.revolt_settings.revolt_js_title,
                            photoSrc: candidate.revolt_settings.revolt_js_photo,
                            overview: candidate.revolt_settings.revolt_js_overview,
                            website: candidate.revolt_settings.revolt_js_website,
                            salary: candidate.revolt_settings.revolt_js_salary,
                            skills: candidate.revolt_settings.revolt_js_skills,
                            availability: candidate.revolt_settings.revolt_js_availability,
                            experience: candidate.revolt_settings.revolt_js_experience,
                            portfolio: candidate.revolt_settings.revolt_js_portfolio
                        };
                    });
                    dispatch(fetchSavedCandidatesSuccess(savedCandidates));
                })
                .catch(err => {
                    console.log(err);
                    dispatch(fetchSavedCandidatesFailed(err))
                })
        } else {
            dispatch(fetchSavedCandidatesSuccess([]));
        }

    }
}