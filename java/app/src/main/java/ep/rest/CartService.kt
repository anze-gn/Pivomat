package ep.rest

import retrofit2.Call
import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory
import retrofit2.http.*

object CartService {

    interface RestApi {

        companion object {
            const val URL = "http://10.0.2.2:8080/netbeans/Pivomat/php/api/"
        }

        @GET("kosarica")
        fun getAll(): Call<List<CartItem>>

        @FormUrlEncoded
        @POST("kosarica")
        fun insert(@Field("idPiva") idPiva: Int,
                   @Field("kolicina") kolicina: Int,
                   @Field("cena") cena: Double,
                   @Field("naziv") naziv: String): Call<String>


        @DELETE("kosarica/{id}")
        fun delete(): Call<List<CartItem>>
    }

    val instance: RestApi by lazy {
        val retrofit = Retrofit.Builder()
                .baseUrl(RestApi.URL)
                .addConverterFactory(GsonConverterFactory.create())
                .build()

        retrofit.create(RestApi::class.java)
    }
}