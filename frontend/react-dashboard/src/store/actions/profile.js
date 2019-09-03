import * as actionTypes from './actionTypes';
import axios from '../../axios-wp-dev-only';

export const fetchProfileStart = () => {
    return {
        type: actionTypes.FETCH_PROFILE_START
    }
}

export const fetchProfileSuccess = profileSettings => {
    /**
     * Dynamically build our payload
     * @returns Example: revolt_company_name: profileSettings.revolt_company_name
     * 
     */
    const returnObj = {};
    for (let key in profileSettings) {
        returnObj[key] = profileSettings[key]
    }

    //Add action type
    returnObj['type'] = actionTypes.FETCH_PROFILE_SUCCESS
    return returnObj;
}

export const profileIsNew = () => {
    return {
        type: actionTypes.PROFILE_IS_NEW
    }
}

export const fetchProfileFailed = error => {
    return {
        type: actionTypes.FETCH_PROFILE_FAILED,
        error: error
    }
}

export const fetchProfile = userName => {
    return dispatch => {
        dispatch(fetchProfileStart());
        axios.get('/wp-json/wp/v2/users/?slug=' + userName)
            .then(response => {
                //Pass profile settings only, settings is false if user is new
                if (response.data[0].revolt_settings === false) {
                    dispatch(profileIsNew());
                } else if (response.data !== null) {
                    dispatch(fetchProfileSuccess(response.data[0].revolt_settings))
                }
            })
            .catch(error => {
                dispatch(fetchProfileFailed(error));
            })
    }
}