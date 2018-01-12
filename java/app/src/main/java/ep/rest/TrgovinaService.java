package ep.rest;

import android.app.Application;
import android.content.Context;
import android.preference.PreferenceManager;
import android.util.Log;

import java.io.IOException;
import java.security.cert.CertificateException;
import java.util.HashSet;
import java.util.List;

import javax.net.ssl.HostnameVerifier;
import javax.net.ssl.SSLContext;
import javax.net.ssl.SSLSession;
import javax.net.ssl.SSLSocketFactory;
import javax.net.ssl.TrustManager;
import javax.net.ssl.X509TrustManager;

import okhttp3.OkHttpClient;
import okhttp3.Interceptor;
import okhttp3.Request;
import okhttp3.Response;
import retrofit2.Call;
import retrofit2.Retrofit;
import retrofit2.converter.gson.GsonConverterFactory;
import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.GET;
import retrofit2.http.Header;
import retrofit2.http.POST;
import retrofit2.http.PUT;
import retrofit2.http.Path;

public class TrgovinaService {

    private static final String TAG = TrgovinaService.class.getCanonicalName();
    private static Context context;


    interface RestApi {
        String URL = "https://10.0.2.2/netbeans/ep-trgovina/php/index.php/api/";

        @GET("izdelki")
        Call<List<Izdelek>> getAll();

        @GET("izdelki/{id}")
        Call<Izdelek> get(@Path("id") int id);

        @FormUrlEncoded
        @POST("izdelki")
        Call<Void> insert(@Field("author") String author,
                          @Field("ime") String title,
                          @Field("cena") double price,
                          @Field("year") int year,
                          @Field("opis") String description);

        @FormUrlEncoded
        @PUT("izdelki/{id}")
        Call<Void> update(@Path("id") int id,
                          @Field("author") String author,
                          @Field("ime") String title,
                          @Field("cena") double price,
                          @Field("year") int year,
                          @Field("opis") String description);

        @FormUrlEncoded
        @POST("prijava")
        Call<LoginState> prijava(@Field("email") String email,
                                 @Field("geslo") String geslo);

        @GET("podatki")
        Call<LoginState> podatki(@Header("Authorization") String token);
    }

    private static RestApi instance;

    public static synchronized RestApi getInstance() {
        if (instance == null) {


            final Retrofit retrofit = new Retrofit.Builder()
                    .baseUrl(RestApi.URL)
                    .addConverterFactory(GsonConverterFactory.create())
                    .client(getUnsafeOkHttpClient())
                    .build();


            instance = retrofit.create(RestApi.class);
        }

        return instance;
    }

    public static synchronized RestApi getInstance(Context appContext) {
        if (context == null) {
            context = appContext;
        }
        return getInstance();
    }

    private static OkHttpClient getUnsafeOkHttpClient() {
        try {
            // Create a trust manager that does not validate certificate chains
            final TrustManager[] trustAllCerts = new TrustManager[]{
                    new X509TrustManager() {
                        @Override
                        public void checkClientTrusted(java.security.cert.X509Certificate[] chain, String authType) throws CertificateException {
                        }

                        @Override
                        public void checkServerTrusted(java.security.cert.X509Certificate[] chain, String authType) throws CertificateException {
                        }

                        @Override
                        public java.security.cert.X509Certificate[] getAcceptedIssuers() {
                            return new java.security.cert.X509Certificate[]{};
                        }
                    }
            };

            // Install the all-trusting trust manager
            final SSLContext sslContext = SSLContext.getInstance("SSL");
            sslContext.init(null, trustAllCerts, new java.security.SecureRandom());
            // Create an ssl socket factory with our all-trusting manager
            final SSLSocketFactory sslSocketFactory = sslContext.getSocketFactory();

            OkHttpClient.Builder builder = new OkHttpClient.Builder();
            builder.sslSocketFactory(sslSocketFactory);
            builder.hostnameVerifier(new HostnameVerifier() {
                @Override
                public boolean verify(String hostname, SSLSession session) {
                    return true;
                }
            });

            OkHttpClient okHttpClient = builder.build();

            return okHttpClient;
        } catch (Exception e) {
            throw new RuntimeException(e);
        }
    }

//    public static class AddCookiesInterceptor implements Interceptor {
//
//
//        private Context context;
//
//        public AddCookiesInterceptor(Context context) {
//            this.context = context;
//        }
//
//        @Override
//        public Response intercept(Chain chain) throws IOException {
//            Request.Builder builder = chain.request().newBuilder();
//            HashSet<String> preferences = (HashSet<String>) PreferenceManager.getDefaultSharedPreferences(context).getStringSet("PREF_COOKIES", new HashSet<String>());
//            for (String cookie : preferences) {
//                builder.addHeader("Cookie", cookie);
//                Log.v("OkHttp", "Adding Header: " + cookie); // This is done so I know which headers are being added; this interceptor is used after the normal logging of OkHttp
//            }
//            return chain.proceed(builder.build());
//        }
//    }
//
//    public static class ReceivedCookiesInterceptor implements Interceptor {
//
//        private Context context;
//
//        public ReceivedCookiesInterceptor(Context context) {
//            this.context = context;
//        }
//
//        @Override
//        public Response intercept(Chain chain) throws IOException {
//            Response originalResponse = chain.proceed(chain.request());
//
//            if (!originalResponse.headers("Set-Cookie").isEmpty()) {
//                HashSet<String> cookies = new HashSet<>();
//
//                for (String header : originalResponse.headers("Set-Cookie")) {
//                    cookies.add(header);
//                    Log.d("OkHttp", "intercept: " + header);
//                }
//
//                PreferenceManager.getDefaultSharedPreferences(context).edit()
//                        .putStringSet("PREF_COOKIES", cookies)
//                        .apply();
//            }
//
//            return originalResponse;
//        }
//    }
}
