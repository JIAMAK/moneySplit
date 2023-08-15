import axios from "axios";
import { useUserMessage } from "@/stores/message";

const baseDomain = import.meta.env.VITE_DEFAULT_API_URL;

const base_URL = `${baseDomain}`;
const instance = axios.create({ baseURL: base_URL, withCredentials: true });

instance.interceptors.request.use(
  (config) => {
    config.validateStatus = function (status) {
      return status <= 500;
    };
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

instance.interceptors.response.use((res) => {
  const message = useUserMessage();
  if (typeof res.data !== "object") {
    message.isVisible = true;
    message.message.type = "error";
    message.message.content = "Ошибка сервера";
    return Promise.reject(res);
  }
  if (res.status !== 200) {
    if (res.status == 500) {
      message.isVisible = true;
      message.message.type = "error";
      message.message.content = res.data.message;
    }
    if (res.status == 400) {
      message.isVisible = true;
      message.message.type = "error";
      message.message.content = res.data.response;
    }
  }

  return res.data;
});
export default instance;
