import { configureStore } from '@reduxjs/toolkit';
import { persistStore } from 'redux-persist';
import reducer from './reducers';

const store = configureStore({
    reducer: reducer,
    middleware: (getDefaultMiddleware) =>
        getDefaultMiddleware({
            serializableCheck: false,
        }),
});
const persister = persistStore(store);

export { store, persister };
