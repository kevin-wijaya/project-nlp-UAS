if __name__ == '__main__':
    ##################################################################
    # LIBRARY ########################################################
    ##################################################################
    #utilities
    import requests, re, nltk, sys, os, numpy as np, pandas as pd, matplotlib.pyplot as plt, seaborn as sns, json
    from wordcloud import WordCloud
    from datetime import datetime, timedelta
    import mysql.connector
    #crawling
    from bs4 import BeautifulSoup
    #preprocessing
    from Sastrawi.StopWordRemover.StopWordRemoverFactory import StopWordRemoverFactory, StopWordRemover, ArrayDictionary
    from Sastrawi.Stemmer.StemmerFactory import StemmerFactory
    import nltk
    from nltk.tokenize import word_tokenize
    #topic modelling
    from sklearn.feature_extraction.text import CountVectorizer
    from gensim.models.coherencemodel import CoherenceModel
    from gensim.models.ldamodel import LdaModel
    from gensim import corpora
    from gensim.models import LsiModel
    import time
    start = time.time()

    ##################################################################
    # RETRIEVE REQUEST ###############################################
    ##################################################################

    #get data
    listDate = []
    #testonly
    # startYear, startMonth, startDay = 2023, 7, 18
    # endYear, endMonth, endDay = 2023, 7, 18
    startYear, startMonth, startDay = sys.argv[1], sys.argv[2], sys.argv[3]
    endYear, endMonth, endDay = sys.argv[4], sys.argv[5], sys.argv[6]
    startDate = datetime(int(startYear), int(startMonth), int(startDay))
    endDate =   datetime(int(endYear), int(endMonth), int(endDay))
    rangeDate = []

    currentDate = startDate
    while currentDate <= endDate:
        listDate.append(currentDate)
        currentDate += timedelta(days=1)


    ##################################################################
    # SCRAPING #######################################################
    ##################################################################

    #start scraping
    listJudul = []
    listTanggal = []
    for date in listDate:
        strDate = str(date.date()).split('-')
        strYear, strMonth, strDay = strDate[0], strDate[1], strDate[2]
        response = requests.get("https://news.detik.com/indeks?date="+strMonth+"%2F"+strDay+"%2F"+strYear)
        bs=BeautifulSoup(response.content,"html.parser")
        pagination=bs.find_all("a",{'class':'pagination__item'})

        for index in range(len(pagination)-1): 
            response2=requests.get("https://news.detik.com/indeks/"+str(index)+"?date="+strMonth+"%2F"+strDay+"%2F"+strYear)
            bs=BeautifulSoup(response2.content,"html.parser")
            #GET CONTAINER ARTICLE
            articles=bs.find("div",{"id":'indeks-container'}).find_all("article")

            for article in articles:
                judul=article.find("h3",{'class':'media__title'}).text
                listJudul.append(judul)
                listTanggal.append(date.date())

    ##################################################################
    # PREPROCESSING ##################################################
    ##################################################################

    df=pd.DataFrame({
        "JudulAsli":listJudul,
        "Tanggal":listTanggal
    })
    #Case Folding
    df["Judul"]=df["JudulAsli"].apply(lambda x:x.lower())

    #Remove Digit - cleaning
    df["Judul"]=df["Judul"].apply(lambda x:re.sub(r"\d+", "", x))

    #Remove Whitespace - cleaning
    df["Judul"]=df["Judul"].apply(lambda x:x.strip())

    #Remove Punctuation - cleaning
    df["Judul"]=df["Judul"].apply(lambda x:re.sub('[^A-Za-z0-9]+', ' ', x))

    #Stopword Removal
    def stopword(text):
        stop_factory = StopWordRemoverFactory().get_stop_words()
        more_stopword = ['tak', 'vs', 'rp','m']
        new_stopword = StopWordRemover(ArrayDictionary(stop_factory + more_stopword))
        return new_stopword.remove(text)
    df["Judul_Stopwords"]=df["Judul"].apply(stopword)

    #Stemming
    def stemming(text):
        factory = StemmerFactory()
        stemmer = factory.create_stemmer()
        return stemmer.stem(text)
    df["Judul_Stemming"]=df["Judul_Stopwords"].apply(stemming)

    # NLTK word tokenize
    def word_tokenize_wrapper(text):
        return word_tokenize(text)
    df["Judul_Tokenize"]=df["Judul_Stemming"].apply(word_tokenize_wrapper)

    # function for top word
    def get_top_n_words(n_top_words, count_vectorizer, text_data):
        vectorized_headlines = count_vectorizer.fit_transform(text_data.values)
        vectorized_total = np.sum(vectorized_headlines, axis=0)
        word_indices = np.flip(np.argsort(vectorized_total)[0,:], 1)
        word_values = np.flip(np.sort(vectorized_total)[0,:],1)
        word_vectors = np.zeros((n_top_words, vectorized_headlines.shape[1]))
        for i in range(n_top_words):
            word_vectors[i,word_indices[0,i]] = 1
        words = [word[0].encode('ascii').decode('utf-8') for 
                word in count_vectorizer.inverse_transform(word_vectors)]
        return (words, word_values[0,:n_top_words].tolist()[0])
    count_vectorizer = CountVectorizer()
    words, word_values = get_top_n_words(n_top_words=15,
                                        count_vectorizer=count_vectorizer, 
                                        text_data=df["Judul_Stemming"])
    
    #display top word
    fig, ax = plt.subplots(figsize=(16,8))
    ax.bar(range(len(words)), word_values)
    ax.set_xticks(range(len(words)))
    ax.set_xticklabels(words, rotation='vertical')
    ax.set_title('Top words in headlines dataset (excluding stop words)')
    ax.set_xlabel('Word')
    ax.set_ylabel('Number of occurences')
    plt.savefig(os.getcwd() + '/img/' + 'top-word.jpg')
    plt.close()
    #tokenizer
    doc_clean=[]
    for i in df["Judul_Stemming"]:
        doc_clean.append(i.split())
    
    #Create Corpus
    dictionary = corpora.Dictionary(doc_clean)
    doc_term_matrix = [dictionary.doc2bow(doc) for doc in doc_clean]

    #evaluation
    coherence_values = []
    model_list = []
    rangeTopic=range(2,11,1)
    for num_topics in rangeTopic:
        # generate LSA model
        model = LsiModel(doc_term_matrix, num_topics=num_topics, id2word = dictionary,chunksize=100)  # train model
        model_list.append(model)
        coherencemodel = CoherenceModel(model=model, texts=doc_clean, dictionary=dictionary, coherence='c_v')
        coherence_values.append(coherencemodel.get_coherence())
    bestNumberTopic=coherence_values.index(max(coherence_values))+2
    number_of_topics=bestNumberTopic

    ##################################################################
    # TOPIC MODELLING LSA ############################################
    ##################################################################

    # LSA Model
    words=10
    modelLSA=LsiModel(doc_term_matrix, num_topics=number_of_topics, id2word = dictionary,chunksize=100)

    #Testing
    topics=[]
    for x in df["Judul_Stemming"]:
        tesbow=dictionary.doc2bow(x.split())
        probability=modelLSA[tesbow]
        listProb=[p[1] for p in probability]
        if listProb==[]:
            topics.append(1)
        else:
            topics.append(listProb.index(max(listProb))+1)
    df["Topic LSA"]=topics

    # World CLoud
    # for i in range(modelLSA.num_topics):
    #     df2=df[df["Topic LSA"]==(i+1)]
    #     text3 = ' '.join(df2['Judul_Stemming'])
    #     wordcloud = WordCloud().generate(text3)
    #     # Generate plot
    #     plt.title("LSA TOPIC "+str(i+1))
    #     plt.imshow(wordcloud)
    #     plt.axis("off")
    #     plt.savefig(os.getcwd() + '/img/' + F'wc-LDA-{i+1}.jpg')
    #     plt.close()

    ##################################################################
    # TOPIC MODELLING LDA ############################################
    ##################################################################
    coherence_values = []
    model_list = []
    numberTopic=range(2,11,1)
    for num_topics in numberTopic:
        # generate LSA model
        model = LdaModel(doc_term_matrix, num_topics=num_topics, id2word = dictionary, alpha='auto',iterations=100)  # train model
        model_list.append(model)
        coherencemodel = CoherenceModel(model=model, texts=doc_clean, dictionary=dictionary, coherence='c_v')
        coherence_values.append(coherencemodel.get_coherence())
    bestNumberTopic=coherence_values.index(max(coherence_values))+2
    #LDA Model
    number_of_topics=bestNumberTopic
    words=10
    
    modelLDA=LdaModel(doc_term_matrix, num_topics=number_of_topics, id2word = dictionary, alpha='auto',iterations=100)

    #Testing
    topics=[]
    for x in df["Judul_Stemming"]:
        tesbow=dictionary.doc2bow(x.split())
        probability=modelLDA.get_document_topics(tesbow)
        listProb=[p[1] for p in probability]
        topics.append(listProb.index(max(listProb))+1)
    df["Topic LDA"]=topics

    #World Cloud
    # sns.countplot(x="Topic LDA",data=df)
    # for i in range(modelLDA.num_topics):
    #     df2=df[df["Topic LDA"]==(i+1)]
    #     text3 = ' '.join(df2['Judul_Stemming'])
    #     wordcloud = WordCloud().generate(text3)
    #     # Generate plot
    #     plt.title("LDA TOPIC "+str(i+1))
    #     plt.imshow(wordcloud)
    #     plt.axis("off")
    #     plt.savefig(os.getcwd() + '/img/' + F'wc-LSA-{i+1}.jpg')
    #     plt.close()

    db = mysql.connector.connect(
        host='localhost',
        user='root',
        password='',
        database='project_uas_nlp'
        )
    
    #clear table
    cursor = db.cursor()
    for sql in ['DELETE FROM data', 'DELETE FROM lsa', 'DELETE FROM lda']:
        cursor.execute(sql)
        db.commit()
    
    #store topic LSA
    cursor = db.cursor()
    for index, topic in modelLSA.show_topics(formatted=False, num_words=10):
        sql = "INSERT INTO lsa (id, name, keyword) \
            VALUES (%s, %s, %s)"
        cursor.execute(sql, (index+1, index+1, ', '.join([w[0] for w in topic])))
        db.commit()

    #store topic LDA
    cursor = db.cursor()
    for index, topic in modelLDA.show_topics(formatted=False, num_words=10):
        sql = "INSERT INTO lda (id, name, keyword) \
            VALUES (%s, %s, %s)"
        cursor.execute(sql, (index+1, index+1, ', '.join([w[0] for w in topic])))
        db.commit()
    
    #store data
    cursor = db.cursor()
    for i, item in df.iterrows():
        sql = "INSERT INTO data (id, tanggal, judul, stopwords, stemming, tokenize, lsa_id, lda_id) \
            VALUES (%s, %s, %s, %s, %s, %s, %s, %s)"
        cursor.execute(sql, (i+1, item['Tanggal'], item['JudulAsli'], item['Judul_Stopwords'], item['Judul_Stemming'], 
                            ', '.join(item['Judul_Tokenize']), item['Topic LSA'], item['Topic LDA']))
        db.commit()
    
    db.close()

    print('success', str(time.time() - start))