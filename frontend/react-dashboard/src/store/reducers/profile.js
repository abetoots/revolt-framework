import * as actionTypes from '../actions/actionTypes';
import { updateObject } from '../../utility/utility';
import { employerInfo } from '../../utility/profileForm';
import clonedeep from 'lodash.clonedeep';

// avoid referencing our imported object
const info = clonedeep(employerInfo);

const initialState = {
    profileInfo: info,
    loaded: false,
    savedCandIds: [],
    loading: false,
    isNew: false,
    error: null
}

const fetchProfileSuccess = (state, action) => {
    const updatedInfo = updateObject(state.profileInfo, {
        revolt_company_name: action.revolt_company_name,
        revolt_headline: action.revolt_headline,
        revolt_established: action.revolt_established,
        revolt_teamSize: action.revolt_teamSize,
        revolt_website: action.revolt_website,
        revolt_description: action.revolt_description,
        revolt_facebook: action.revolt_facebook,
        revolt_twitter: action.revolt_twitter,
        revolt_linkedin: action.revolt_linkedin,
        revolt_phoneNum: action.revolt_phoneNum,
        revolt_companyCat: action.revolt_companyCat,
        revolt_contactEmail: action.revolt_contactEmail
    });
    return updateObject(state, {
        profileInfo: updatedInfo,
        savedCandIds: action.revolt_saved_candidates,
        loading: false,
        loaded: true,
        isNew: false
    });
}

const fetchProfileFailed = (state, action) => {
    return updateObject(state, {
        error: action.error,
        loading: false
    })
}

const reducer = (state = initialState, action) => {
    switch (action.type) {
        case actionTypes.FETCH_PROFILE_START: return updateObject(state, { loading: true, error: null });
        case actionTypes.FETCH_PROFILE_SUCCESS: return fetchProfileSuccess(state, action);
        case actionTypes.FETCH_PROFILE_FAILED: return fetchProfileFailed(state, action);
        case actionTypes.PROFILE_IS_NEW: return updateObject(state, { loading: false, loaded: true, isNew: true });
        default: return state;
    }
}

export default reducer;