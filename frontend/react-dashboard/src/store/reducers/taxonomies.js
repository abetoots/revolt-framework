import * as actionTypes from '../actions/actionTypes';
import { updateObject } from '../../utility/utility';

const initialState = {
    taxonomies: [],
    loading: false,
    error: null,
    fetched: false,
}

const reducer = (state = initialState, action) => {
    switch (action.type) {
        case actionTypes.FETCH_TAXONOMIES_START:
            return updateObject(state, { loading: true, error: null, fetched: false })
        case actionTypes.FETCH_TAXONOMIES_SUCCESS:
            return updateObject(state, { taxonomies: action.taxonomies, loading: false, error: null, fetched: true })
        case actionTypes.FETCH_TAXONOMIES_FAILED:
            return updateObject(state, { error: action.error, loading: false, fetched: false })
        default:
            return state;
    }
}

export default reducer;
