import { useRouter } from "vue-router";

export default function authGuard() {
  const router = useRouter();

  if (!localStorage.getItem("token")) {
    router.push("/login");
  }
}
