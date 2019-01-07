package ep.rest


import java.io.Serializable

data class CartItem(
        val id: Int = 0,
        val idPiva: Int = 0,
        val kolicina: Int = 0,
        val naziv: String = "",
        val cena: Double = 0.0
) : Serializable
