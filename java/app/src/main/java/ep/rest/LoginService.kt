package ep.rest

import retrofit2.Call
import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory
import retrofit2.http.*

object LoginService {

    interface RestApi {

        companion object {
            const val URL = "https://10.0.2.2:8443/netbeans/Pivomat/php/api/"
        }

//        @GET("piva")
//        fun getAll(): Call<List<Pivo>>

        @GET("odjava")
        fun odjava(@Header("Cookie") cookie : String): Call<String>
        // @Header("Cookie") cookie : Cookie

        @FormUrlEncoded
        @POST("prijava")
        fun prijava(@Field("email") email: String,
                   @Field("geslo") geslo: String,
                   @Field("_qf__prijava") prijava: String): Call<String>

//        @FormUrlEncoded
//        @PUT("books/{id}")
//        fun update(@Path("id") id: Int,
//                   @Field("author") author: String,
//                   @Field("title") title: String,
//                   @Field("price") price: Double,
//                   @Field("year") year: Int,
//                   @Field("description") description: String): Call<Void>
    }

    val instance: RestApi by lazy {
        val retrofit = Retrofit.Builder()
                .baseUrl(RestApi.URL)
                .addConverterFactory(GsonConverterFactory.create())
                .client(myOkHttpClient.getUnsafeOkHttpClient())
                .build()

        retrofit.create(RestApi::class.java)
    }
}