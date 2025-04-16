export type IRegister = {
  name: string,
  password_confirmation: string
} & ILogin;

export type ILogin = {
  email: string,
  password: string
};