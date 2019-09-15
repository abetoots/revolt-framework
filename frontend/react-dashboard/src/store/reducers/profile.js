import * as actionTypes from '../actions/actionTypes';
import { updateObject } from '../../utility/utility';

const initialState = {
    profileInfo: [],
    loaded: false,
    loading: false,
    isNew: false,
    error: null
}

const fetchProfileSuccess = (state, action) => {
    return updateObject(state, {
        profileInfo: action.profileInfo,
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
        case actionTypes.PROFILE_IS_NEW: return updateObject(state, { loading: false, loaded: false, isNew: true });
        default: return state;
    }
}

export default reducer;