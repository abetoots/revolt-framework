import * as actionTypes from '../actions/actionTypes';
import { updateObject } from '../../utility/utility';

const initialState = {
    jobs: [],
    loaded: false,
    error: false,
    loading: false
}

const reducer = (state = initialState, action) => {
    switch (action.type) {
        case actionTypes.FETCH_JOBS_START:
            return updateObject(state, { error: false, loading: true })
        case actionTypes.FETCH_JOBS_SUCCESS:
            return updateObject(state, { jobs: action.data, error: false, loading: false, loaded: true })
        case actionTypes.FETCH_JOBS_FAILED:
            return updateObject(state, { error: action.error, loading: false })
        default:
            return state;
    }
}

export default reducer;