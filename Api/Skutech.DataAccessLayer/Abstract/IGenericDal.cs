namespace Skutech.DataAccessLayer.Abstract;

public interface IGenericDal<T> where T : class
{
    void Insert(T entity);
    void Update(T entity);
    void Delete(T entity);
    T GetElementByID(int id);
    List<T> GetList();
}