import { persistReducer } from 'redux-persist';
import storage from 'redux-persist/lib/storage';

import authSlice from './authSlice';
import { combineReducers } from '@reduxjs/toolkit';

// ==============================|| COMBINE REDUCER ||============================== //

const reducer = combineReducers({
    auth: persistReducer({ key: 'Data', storage }, authSlice),
});

export default reducer;
