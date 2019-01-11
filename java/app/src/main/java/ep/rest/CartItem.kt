package ep.rest


import java.io.Serializable

data class CartItem(
        val id: Int = 0,
        val kol: Int = 0,
        val naziv: String = "",
        val cena: Double = 0.0
) : Serializable
