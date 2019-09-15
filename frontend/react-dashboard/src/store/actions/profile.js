import * as actionTypes from './actionTypes';
import axios from '../../axios-instance';

export const fetchProfileStart = () => {
    return {
        type: actionTypes.FETCH_PROFILE_START
    }
}

export const fetchProfileSuccess = profileData => {
    return {
        type: actionTypes.FETCH_PROFILE_SUCCESS,
        profileInfo: profileData
    };
}

export const fetchProfileFailed = error => {
    return {
        type: actionTypes.FETCH_PROFILE_FAILED,
        error: error
    }
}

export const fetchProfile = () => {
    return dispatch => {
        dispatch(fetchProfileStart());
        let userName = localStorage.getItem('userName');
        console.log(userName)
        if (userName) {
            axios.get(`/wp-json/wp/v2/users/?slug=${userName}`)
                .then(response => {
                    if (response.data[0].revolt_settings) {
                        dispatch(fetchProfileSuccess(response.data[0]))
                    } else {
                        console.log('here');
                        dispatch({ type: actionTypes.PROFILE_IS_NEW });
                    }
                })
                .catch(error => {
                    dispatch(fetchProfileFailed(error));
                })
        } else {
            dispatch(fetchProfileFailed('Missing username'));
        }

    }
}