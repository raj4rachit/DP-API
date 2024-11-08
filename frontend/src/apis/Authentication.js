import { LoginURL, LogoutURL } from '@utils/ApiConstant';
import { AxiosServices } from '@utils/AxiosService';

export function LoginAPI(data) {
    return AxiosServices.post(LoginURL, data);
}

export function LogoutAPI() {
    return AxiosServices.post(LogoutURL);
}
