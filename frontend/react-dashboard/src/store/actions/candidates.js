import * as actionTypes from './actionTypes';
import axios from '../../axios-wp-dev-only';

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
        axios.get("/wp-json/wp/v2/users?roles=jobseeker", { headers: { "Authorization": "Bearer " + token } })
            .then(response => {
                const fetchedCandidates = [];
                for (let user of response.data) {
                    if (user.revolt_settings) {
                        fetchedCandidates.push({
                            id: user.id,
                            name: user.revolt_settings.revolt_js_name,
                            title: user.revolt_settings.revolt_js_title,
                            overview: user.revolt_settings.revolt_js_overview,
                            photoSrc: user.revolt_settings.revolt_js_photo,
                            website: user.revolt_settings.revolt_js_website,
                            salary: user.revolt_settings.revolt_js_salary,
                            skills: user.revolt_settings.revolt_js_skills,
                            availability: user.revolt_settings.revolt_js_availability,
                            experience: user.revolt_settings.revolt_js_experience,
                            portfolio: user.revolt_settings.revolt_js_portfolio
                        });
                    }
                }
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

export const fetchSavedCandidates = userIdArray => {
    return dispatch => {
        dispatch(fetchSavedCandidatesStart());
        let urlString = '/wp-json/wp/v2/users?include=';
        for (const num of userIdArray) {
            urlString += `${num},`;
        }
        console.log(urlString);
        axios.get(urlString)
            .then(response => {
                console.log(response);
                const savedCandidates = [];
                for (let user of response.data) {
                    savedCandidates.push({
                        id: user.id,
                        name: user.revolt_settings.revolt_jobseeker_name,
                        title: user.revolt_settings.revolt_job_title,
                        photoSrc: user.revolt_settings.revolt_profile_photo,
                    });
                }
                dispatch(fetchSavedCandidatesSuccess(savedCandidates));
            })
            .catch(err => {
                console.log(err);
                dispatch(fetchSavedCandidatesFailed(err))
            })
    }
}