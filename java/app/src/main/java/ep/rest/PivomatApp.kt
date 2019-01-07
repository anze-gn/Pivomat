package ep.rest

import android.app.Application
import java.util.*

class PivomatApp : Application() {

    private var email: String? = null
    private var password: String? = null


    private var kosarica = LinkedList<Pivo>()

    fun getKosarica(): LinkedList<Pivo> {
        return kosarica
    }

    fun setKosarica(kosarica: LinkedList<Pivo>) {
        this.kosarica = kosarica
    }

    fun addKosarica(p: Pivo) {
        kosarica.add(p)
    }

    fun izprazniKosarico() {
        kosarica = LinkedList()
    }

    fun getPassword(): String? {

        return password
    }

    fun setPassword(password: String) {
        this.password = password
    }

    fun getEmail(): String? {

        return email
    }

    fun setEmail(email: String) {
        this.email = email
    }

}